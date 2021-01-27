import json

class Utils:

    api_urls = {
        "base": "http://localhost/files/api.tasks/src/main.php",
        "users": "http://localhost/files/api.tasks/src/main.php/users"
    }


    @staticmethod
    def getUserData():
        with open('.test-account-info.json') as configFile:
            configData = json.loads(configFile.read())
            return configData
    




