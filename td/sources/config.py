import json

class Config:
    """Holds the configuration data
    """
    SpawnerConfig: dict
    EnemyConfig: dict
    PlayerConfig: dict
    TowerConfig: list

    @classmethod
    def loadData(cls) -> None:
        with open("./td/config/rules.json", "r") as file:
            jsonData = json.load(file)
        
            Config.SpawnerConfig = jsonData["SPAWNER"]
            Config.PlayerConfig = jsonData["PLAYER"]
            Config.EnemyConfig = jsonData["ENEMY"]
            Config.TowerConfig = jsonData["TOWERS"]