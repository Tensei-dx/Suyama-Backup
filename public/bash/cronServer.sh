#!/bin/bash

#Input PROJECT FOLDER NAME
PROJECT_FOLDER_NAME="iBMS_L7"

PROJECT_PATH="/usr/share/nginx/html/${PROJECT_FOLDER_NAME}/"
PROJECT_ENV="$PROJECT_PATH/.env"

#export .env file
export $(grep -v '^#' $PROJECT_ENV | tr -d ' ' | xargs -d '\n' )

#parse Server List
ip_array=($(echo $SERVER_LIST | tr "," "\n"))

#Check array size
if [ ${#ip_array[@]} -ge 2 ]; then
    master_server=${ip_array[0]}    #MASTER_SERVER
    slave_server=${ip_array[1]}     #SLAVE_SERVER

    #Check if the server is a MASTER or a SLAVE
    if [ $SERVER_TYPE == "MASTER" ]; then
        php "$PROJECT_PATH"artisan schedule:run    #execute command
    elif [ $SERVER_TYPE == "SLAVE" ]; then

        #Ping Master Server
        if ping -c1 -W1 $master_server; then    #Master Server (alive)
                echo $(date)" $master_server (alive) ✓" >> "/root/cronSample/cronServer.log";
        else                                    #Master Server (dead)
                echo $(date)" $master_server (dead)  ✗ Execute Slave CRON" >> "/root/cronSample/cronServer.log";
                php "$PROJECT_PATH"artisan schedule:run    #execute command
        fi
    fi
fi

