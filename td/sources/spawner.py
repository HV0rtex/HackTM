from config import Config
from enemy import Enemy

class Spawner:
    """Models an enemy spawner
    """

    def __init__(self, position: tuple(float, float), lifetime: int, route: list(tuple(float, float))) -> None:
        self.__lat, self.__long = position
        self.__lifetime = lifetime
        self.__route = route

    def tick(self) -> (list or None):
        self.__lifetime -= 1

        if self.__lifetime % Config.SpawnerConfig["SPAWN_RATE"] == 0:
            return Enemy((self.__lat, self.__long), self.__route)
        return None

    def isExpired(self) -> bool:
        return self.__lifetime <= 0