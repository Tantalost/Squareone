from flask import Flask, render_template
from flask import request
from markupsafe import escape

app = Flask(__name__)

#The main homepage of the webapp
@app.route('/')
def home():
    name = escape(request.form.get('name'))
    return render_template('index.html', username=name)


#The page for testing Forms
@app.route('/form', methods = ['GET', 'POST'])
def form():
    if request.method == 'POST':
        name = escape(request.form.get('name'))
    else:
        name = 'Guest'
    return render_template ('form.html', username=name)

if __name__ == '__main__':
    app.run(debug=True)