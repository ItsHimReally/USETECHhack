"""
Основной файл API
Документация по работе с API находится на GitBook
"""
import werkzeug.exceptions
from flask import Flask, jsonify, request, abort
from flask_mysqldb import MySQL
from loguru import logger
from flask_cors import CORS
from dotenv import load_dotenv, find_dotenv
import requests
from dotenv import load_dotenv, find_dotenv
import os
from db_connect import db

app = Flask(__name__)
# cors = CORS(app, resources={r"/v1/*": {"origins": "https://USEcure.ru"}})

load_dotenv(find_dotenv())

token = os.environ.get("API_AUTH_TOKEN")
root_url = os.environ.get("ROOT_URL")
realm = os.environ.get("REALM")

mysql = MySQL(app)



@app.errorhandler(werkzeug.exceptions.HTTPException)
def exceptions_info(exception):
    if exception.description != 'Error in code':
        logger.critical(
            f'-- {request.headers.get("X-Real-IP")} -- {request.url} -- [ERROR] [{exception.code}] [{exception.name}] [{exception.description}]')

    resp = jsonify({"errorCode": exception.code, "errorName": exception.name, "errorDescript": exception.description})
    return resp, exception.code


@app.route('/')
def hello():
    return '<pre>{"msg": "USEcure API is successfully configured and working"}</pre>'


@app.route('/create_user', methods=['GET'])
def create_user():
    client_id = request.args.get('client_id')
    user_name = request.args.get('user_name')
    name  = request.args.get('name')
    surname = request.args.get('surname')
    roles = request.args.get('roles')
    attribute = request.args.get('attribute')
    comment = request.args.get('comment')

    url = f'http://{root_url}/auth/admin/realms/{realm}/clients'

    headers = {
        'Authorization': f'Bearer {token}',
    }    

    client_settings = {
        "protocol": "openid-connect",
        "clientId": client_id,
        "enabled": True,
        "publicClient": False,
        "standardFlowEnabled": True,
        "directAccessGrantsEnabled": True,
        "serviceAccountsEnabled": True,
    }
    resp = requests.post(
        url,
        json=client_settings,
        headers=headers,
    )
    resp.raise_for_status()

    params = [client_id, user_name, name, surname, roles, attribute, comment]
    db("INSERT INTO `users` (`id`, `client_id`, `user_name`, `name`, `surname`, `role`, `attribute`, `comment`) VALUES (NULL, %s, %s, %s, %s, %s, %s, %s)", params)
    return f'created user {user_name}'

@app.route('/create_service', methods=['GET'])
def create_service():
    url  = request.args.get('url')
    name  = request.args.get('name')
    role  = request.args.get('role')
    attribute  = request.args.get('attribute')
    comment  = request.args.get('comment')

    params = [url, name, role, attribute, comment]
    db("INSERT INTO `services` (`id`, `url`, `name`, `role`, `attribute`, `comment`) VALUES (NULL, %s, %s, %s, %s, %s)", params)
    return f'created service {name} at {url}'

if __name__ == "__main__":
    app.run(host='0.0.0.0')