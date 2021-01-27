
from Utils import Utils
import requests
import json

userData = Utils.getUserData()


headers = {
    "X-USER-ID": userData["id"]
}


parms = {
    "id": 123,
}

r = requests.post(Utils.api_urls["events"], headers=headers, data=parms)

print(r.text)





