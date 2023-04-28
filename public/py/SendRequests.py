# For testing purposes

import sys
import json
import base64
import requests

def send_get_request(URL, BODY):
    """ Send GET request """
    # Initiate session for Requests
    session = requests.Session()
    # Decode API Authorization
    credentials = json.loads(base64.standard_b64decode(sys.argv[1]).decode('utf-8').rstrip())
    # Implement decoded credentials to the session
    session.auth = (credentials['username'], credentials['password'])
    # Decode URL in base64
    url_decoded = base64.standard_b64decode(URL).decode('utf-8').rstrip()
    # If the request does not need a body, the BODY is equal to noBody
    if BODY == 'noBody':
        url_complete = url_decoded
    else :
        data_decoded = base64.standard_b64decode(BODY).decode('utf-8').rstrip()
        url_complete = '{}?{}'.format(url_decoded, data_decoded)
    # Send the request, SSL certificate verification is turned off
    response = session.get(url = url_complete, verify = False)
    # Parse output to json
    output = response.json()
    # Print output
    print(json.dumps(output))


## SCRIPT

if len(sys.argv) == 4:
    url = sys.argv[2]
    body = sys.argv[3]
    send_get_request(url, body)
else :
    print('Error: Not enough arguments')
# Create checker id there is data