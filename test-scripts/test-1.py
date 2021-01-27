
from Utils import Utils
import requests
import json

userData = Utils.getUserData()


headers = {
    "X-USER-ID": userData["id"]
}

r = requests.get(Utils.api_urls["users"], headers=headers)

print(r.json())





