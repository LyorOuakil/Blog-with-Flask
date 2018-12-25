from flask import render_template, url_for, flash, redirect, jsonify, request
from flaskblog import app
from flaskblog.forms import RegistrationForm, LoginForm
from flaskblog.model import User
from werkzeug.security import generate_password_hash, check_password_hash
from flaskblog import db
posts = []

@app.route("/")
@app.route("/home")
def home():
    return render_template('home.html', posts=posts)

@app.route("/about")
def about():
    return render_template('about.html', title='About')

#====================================API========================================================#

@app.route('/api/user/<user_id>', methods=['DELETE'])
def delete_one_users(user_id):
    user = User.query.filter_by(id=user_id).first()
    if not user:
        return jsonify({'message' : 'user doesnt exist'})
    db.session.delete(user)    
    db.session.commit()
    return jsonify({'message' : 'user deleted'})

@app.route('/api/user/<user_id>', methods=['GET'])
def get_one_users(user_id):
    user = User.query.filter_by(id=user_id).first()
    if not user:
        return jsonify({"message" : "User does not exist"})
    output = []
    user_data = {}
    user_data['id'] = user.id
    user_data['username'] = user.username
    user_data['password'] = user.password
    user_data['email'] = user.email
    output.append(user_data)
    return jsonify({'users' : output})

@app.route('/api/user/<user_id>', methods=['PUT'])
def Update_one_users(user_id):
    user = User.query.filter_by(id=user_id).first()
    if not user:
        return jsonify({"message" : "User does not exist"})
    
    data = request.get_json()
    print("EMAIL / ", data['email'])
    user.email = data['email']
    user.password = data['password']
    user.username = data['username']
    db.session.commit()
    return jsonify({"message" : "User  has been updated"})

@app.route("/api/register", methods=['POST'])
def CreateUser():
    data = request.get_json()
    hashed_password = generate_password_hash(data['password'], method='sha256')
    new_user = User(username=data['username'], email=data['email'], password=hashed_password)
    db.session.add(new_user)
    db.session.commit()
    return jsonify({"message" : "User has been created"})

@app.route('/api/user', methods=['GET'])
def get_all_users():
    users = User.query.all()
    print(users)
    output = []
    for user in users:
        user_data = {}
        user_data['id'] = user.id
        user_data['username'] = user.username
        user_data['password'] = user.password
        user_data['email'] = user.email
        output.append(user_data)
    return jsonify({'users' : output})

#====================================API======================================================#

@app.route("/register", methods=['GET', 'POST'])
def register():
    form = RegistrationForm()
    if form.validate_on_submit():
        hashedPassword = generate_password_hash(form.password.data)
        username = form.username.data
        email = form.email.data
        insertUser(hashedPassword, username, email)
        return redirect(url_for('home')) 
    return render_template('register.html', title="Register", form=form) 

@app.route("/login", methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        data = request.form.to_dict()
        email = data['email']
        passwordDatabase = getPassword(email)
        password = check_password_hash(passwordDatabase[0] ,data['password'])
        if password:
            return redirect(url_for('home'))
        return render_template('login.html', title='login', form=form)
    else:
        form = LoginForm()
        if form.validate_on_submit():
            email = form.email.data
            passwordDatabase = getPassword(email)
            password = check_password_hash(passwordDatabase[0], form.password.data)
            if password:
                return redirect(url_for('home'))
        return render_template('login.html', title='Login', form=form)
