from django.template.loader import render_to_string
from django.shortcuts import render
from django.http import HttpResponse

import json
import numpy as np

from td.sources.ai2 import Agent, perform_action

agent = Agent()
prev = np.zeros(shape=(10000,))

# Create your views here.
def index(request):
    return render(request, 'index.html')

def request_ai_action(request, reward):
    if request.method == "POST":
        state = request.body.decode('utf-8')
        state = json.loads(state)

        act = agent.act(state)
        agent.learn(prev, act, reward, state)
        prev = state

        return HttpResponse(perform_action(act))