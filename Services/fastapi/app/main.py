from fastapi import FastAPI, HTTPException, Request
import jwt
import mysql.connector
from dotenv import load_dotenv, find_dotenv
import os

app = FastAPI()

load_dotenv(find_dotenv())

KEY = os.environ.get("SECRET_KEY")

mydb = mysql.connector.connect(
    host=os.environ.get("MYSQL_HOST"),
    user=os.environ.get("MYSQL_USER"),
    password=os.environ.get("MYSQL_PASSWORD"),
    database=os.environ.get("MYSQL_DATABASE")
)


def get_urls(sql):
    mycursor = sql.cursor()
    mycursor.execute(f"""SELECT `url` FROM services""")
    result = [i[0] for i in mycursor.fetchall()]

    mycursor.close()

    return result


def get_url_by_pattern(urls, pattern):
    ln = 0
    res = ''
    for url in urls:
        if pattern.startswith(url) and len(url) > ln:
            ln = len(url)
            res = url

    return res


def create_log(sql, client_id, error_code, service_url, iss):
    mycursor = sql.cursor()
    mycursor.execute(
        """INSERT INTO logs (datetime, client_id, errorCode, service_url, iss) VALUES (NOW(), %s, %s, %s, %s)""",
        [client_id, error_code, service_url, iss])
    sql.commit()

    mycursor.close()
    return None


def necessary_roles(sql, service_url, client_id):
    mycursor = sql.cursor()

    mycursor.execute("""SELECT attribute,role FROM users WHERE client_id=%s""", [client_id])
    user_info = mycursor.fetchall()[0]

    user_attribute = user_info[0]
    user_roles = user_info[1].split(',')

    urls = get_urls(sql)
    service_url = get_url_by_pattern(urls, service_url)

    mycursor.execute(
        """SELECT attribute,role FROM services WHERE url=%s""", [service_url])
    service_info = mycursor.fetchall()[0]

    service_attribute = service_info[0]

    if service_info[1] == '*': # Если в ролях стоит звездочка то доступ открыт
        return True

    service_roles = service_info[1].split(',')

    service_roles = set(service_roles)
    user_roles = set(user_roles)

    union = service_roles & user_roles
    mycursor.close()

    return union != set() or service_attribute <= user_attribute


def decode_jwt_token(token: str):
    decode = jwt.decode(token, '-----BEGIN PUBLIC KEY-----\n' + KEY + '\n-----END PUBLIC KEY-----', algorithms='RS256',
                        audience='account')
    return decode


@app.get("/check")
async def read_user(request: Request):
    token = request.headers.get('token')
    uri = request.headers.get('x-original-uri')
    x_forwarded_proto = request.headers.get('x-forwarded-proto')
    host = request.headers.get('host')

    service_url = x_forwarded_proto + '://' + host + uri

    try:
        decode = decode_jwt_token(token)
    except (jwt.exceptions.ExpiredSignatureError, jwt.exceptions.InvalidSignatureError, jwt.exceptions.DecodeError):
        create_log(mydb, 'error', 403, service_url, 'error')
        raise HTTPException(status_code=403, detail="Forbidden")

    clientId = decode['clientId']
    iss = decode['iss']

    urlsa = get_urls(mydb)

    if not get_url_by_pattern(urlsa, service_url) or not necessary_roles(mydb, service_url, clientId):
        create_log(mydb, clientId, 403, service_url, iss)
        raise HTTPException(status_code=403, detail="Forbidden")

    create_log(mydb, clientId, 200, service_url, iss)

    return 200
