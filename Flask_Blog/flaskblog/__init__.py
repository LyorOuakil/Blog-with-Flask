from flask import Flask
from flask_sqlalchemy import SQLAlchemy
# from werkzeug.security import generate_password_hash, check_password_hash
# from model import *

app = Flask(__name__)
app.config['SECRET_KEY'] = '5791628bb0b13ce0c676dfde280ba245'
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:////Users/Lyor/Desktop/coding-academy/Flask_D01/Flask/Flask_Blog/database.db'
db = SQLAlchemy(app)


from flaskblog import Controller 
