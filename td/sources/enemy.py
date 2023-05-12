from config import Config

class Enemy:
    def __init__(self, spawner_position: tuple(float, float), route: list(tuple(float, float))) -> None:
        self.__posInRoute = 0
        self.__lat, self.__long = spawner_position
        self.__hp = Config.EnemyConfig["HEALTH"]
        self.__armor = Config.EnemyConfig["ARMOR"]
        self.__speed = Config.EnemyConfig["SPEED"]
        self.__damage = Config.EnemyConfig["DAMAGE"]
        self.__route = route

    def takeDamage(self, damage: int) -> None:
        self.__hp -= (damage - self.__armor)

    def move(self) -> None:
        self.__posInRoute = min(self.__posInRoute + self.__speed, len(self.__route))
        self.__lat, self.__long = self.__route[self.__posInRoute]

    def getPosition(self) -> tuple(float, float):
        return (self.__lat, self.__long)
    
    def dealDamage(self) -> bool:
        Config.PlayerConfig["HEALTH"] -= self.__damage

        return Config.PlayerConfig["HEALTH"] <= 0