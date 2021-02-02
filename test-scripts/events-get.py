from Utils import Utils
import requests
import json
import uuid

userData = Utils.getUserData()


headers = {
    "X-USER-ID": userData["id"]
}


parms = {
    "starts_on": "2021-02-01",
    "ends_on": "2021-03-08",
}

r = requests.get(Utils.api_urls["events"], headers=headers, params=parms)

print(r.text)

