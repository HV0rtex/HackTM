from ai2 import Agent, perform_action
import urllib.request
import json
import sched, time
import numpy as np

agent = Agent()
prev_state = np.zeros(10000,)
prev_action = 0
state = np.zeros(shape=(10000,))

def build_request(action):
    myurl = "http://jvp.ro/georacers-td/files/php/ai_post.php"
    body = perform_action(action, agent.legalize_action(state, action))

    print("Sent request: " + body)

    req = urllib.request.Request(myurl)
    req.add_header('Content-Type', 'application/json; charset=utf-8')
    jsondataasbytes = body.encode('utf-8')   # needs to be bytes
    req.add_header('Content-Length', len(jsondataasbytes))
    response = urllib.request.urlopen(req, jsondataasbytes)

    return response.read()

def build_state(data):
    reward = data['CLOSEST_DEATH']
    next_state = np.zeros(shape=(10000,))

    for obj in data['OBJECTS']:
        coordX = 0
        coordY = 0

        if obj['LAT'] > 0:
            coordX += 50
        else:
            obj['LAT'] *= -1
        
        if obj['LNG'] > 0:
            coordY += 50
        else:
            obj['LNG'] *= -1
        
        coordX += obj['LAT'] // 0.0004
        coordY += obj['LNG'] // 0.0004

        next_state[100 * int(coordY) + int(coordX)] = obj['TYPE']
    return next_state

def learn_ai(response):
    string = response.decode('utf-8')
    data = json.loads(string)

    state = build_state(data)

    agent.learn(prev_state, prev_action, -1 * data["CLOSEST_DEATH"], state, 0)

def make_move():
    action = agent.act(state)
    response = build_request(action)

    print(response)

    if len(response) > 0:
        learn_ai(response)
        prev_action = action
        prev_state = state

if __name__ == '__main__':
    while True:
        make_move()
        time.sleep(7)
        