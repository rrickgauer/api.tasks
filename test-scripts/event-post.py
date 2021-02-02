
from Utils import Utils
import requests
import json
import uuid

userData = Utils.getUserData()


headers = {
    "X-USER-ID": userData["id"]
}


parms = {
    "id": uuid.uuid1(),
    "name": "from python",
}

r = requests.post(Utils.api_urls["events"], headers=headers, data=parms)

print(r.text)
