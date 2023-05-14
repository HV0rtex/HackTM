import numpy as np
from tensorflow import keras
from json import dumps

# -0.02  --- +0.02
# 1 bucata = 0.0004
# 100 * 100

# map of 10.000
#   - Turn : cod = tip_turn
#   - Spawn : cod = 100
#
# Safe-zone: -0.008 --- 0.008

class Agent:
    def __init__(self) -> None:
        self.__model = keras.Sequential([
            keras.layers.Dense(64, activation='relu', input_shape=(10000,)),
            keras.layers.Dense(128, activation='relu'),
            keras.layers.Dense(128, activation='sigmoid'),
            keras.layers.Dropout(0.2),
            keras.layers.Dense(20000, activation='softmax')
        ])
        
        self.__model.compile(optimizer=keras.optimizers.Adam(learning_rate=0.001), loss='mse')

        self.__learning_rate = 0.001
        self.__discount_factor = 0.99
        self.__firstMove = 0

    def act(self, state):
        epsilon = 0.01

        if np.random.rand() < epsilon:
            action = np.random.choice([i for i in range(20000)])
        else:
            q_values = self.__model.predict(np.array([state]))
            action = np.argmax(q_values)

        if self.__firstMove == 0 and not self.legalize_action(state, action):
            space = []
            for i in range(20000):
                if self.legalize_action(state, i):
                    space.append(i)

            action = np.random.choice(space)
            self.__firstMove = 1

        return action
    
    def legalize_action(self, state, action):
        numSpawners = 0
        for i in range(10000):
            if state[i] == 100:
                numSpawners += 1

        if action >= 10000 and numSpawners < 1:
            return False
        if action < 10000 and numSpawners == 2:
            return False


        if (action % 100 < 20) or (action % 100 >= 50 and action % 100 < 70):
            return False
        if (action // 100 < 20) or (action // 100 >= 50 and action // 100 < 70):
            return False
        
        coords = action
        if action > 10000:
            coords -= 10000

        if (state[coords] != 0 and action < 10000) or (state[coords] != 100 and action > 10000):
            return False
        return True

    def getNextState(self, state, action):
        if action >= 10000:
            state[action - 10000] = 0
        else:
            state[action] = 100

        return state

    def learn(self, state, action, reward, next_state, done):
        # Update the neural network weights using Q-learning
        q_values = self.__model.predict(np.array([state]))
        next_q_values = self.__model.predict(np.array([next_state]))
        max_next_q_value = np.max(next_q_values)

        td_target = reward + self.__discount_factor * max_next_q_value * (1 - done)
        td_error = td_target - q_values[0, action]

        q_values[0, action] += self.__learning_rate * td_error
        self.__model.fit(np.array([state]), q_values, verbose=0)

def perform_action(action, isLeagal):
    action_json = {
        "TYPE": 0,
        "LAT": 0,
        "LNG": 0
    }

    print(action)

    if action > 10000:
        action_json["TYPE"] = -1
        action -= 10000
    else:
        action_json["TYPE"] = 1

    if not isLeagal:
        action_json["TYPE"] = 0
        action_json["LAT"] = 0
        action_json["LNG"] = 0

        return dumps(action_json)

    if action // 100 > 50:
        action_json["LNG"] = int(action // 100 - 50) * 0.0004
    else:
        action_json["LNG"] = int(action // 100) * -0.0004
    
    if action % 100 > 50:
        action_json["LAT"] = int(action % 100 - 50) * 0.0004
    else:
        action_json["LAT"] = int(action % 100) * -0.0004

    return dumps(action_json)