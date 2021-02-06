
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
    "frequency": "WEEKLY",
    "seperation": 2,
    "recurrence_day": 6,
    "starts_on": "2021-02-06",
    "ends_on": "2022-02-06",
}

r = requests.post(Utils.api_urls["events"], headers=headers, data=parms)

print(r.text)
