import pymysql
from pymysql import cursors
import os
from dotenv import load_dotenv, find_dotenv


load_dotenv(find_dotenv())


db_host = os.environ.get("DB_HOST")
db_port = int(os.environ.get("DB_PORT"))
db_user = os.environ.get("DB_USER")
db_password = os.environ.get("DB_PASSWORD")
db_name = os.environ.get("DB_NAME")

def db(req, params):
        connection = pymysql.connect(
            host=db_host,
            port=db_port,
            user=db_user,
            password=db_password,
            database=db_name,
            cursorclass=cursors.DictCursor
        )
        with connection.cursor() as cursor:
            cursor.execute(req, params)
            connection.commit()
            cursor.fetchall()
            cursor.close()
            connection.close()