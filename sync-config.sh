#!/bin/bash
function pingEtcd {
	#尝试ping 2次, 不输出错误信息
	ping -c 2 -t 2 etcd.etcd-ha > /dev/null 2>&1
	echo $?
}

function syncConfig {
	confd -confdir="$1" -onetime -backend etcd -node http://etcd.etcd-ha:2379
}

function pingConfigs {
	for item in `sed -n "/@ping/{n; s/[',]//g; p;}" config.php | awk -F '=>' '{print $2}'`
	do
		local url=`echo $item | sed "{s/http://g; s/\///g;}"`
		echo "ping $url test"
		ping -c 2 -t 2 $url > /dev/null 2>&1
		if [ $? -ne 0 ]
		then
			echo "ping $url fail"
		else
			echo "ping $url sucess"
		fi
	done

}

#尝试5次
checkTimes=0
while [ `pingEtcd` -ne 0 ]
do
	sleep 1
	let checkTimes++
	
	if [ $checkTimes -gt 5 ]
	then
		echo 'check connection fail!'
		exit 1
	fi
done

#检测没有问题后同步配置文件
if [ $1 == "sandbox" ]
then
	if [ $(syncConfig "conf/sandbox") -ne 0 ]
	then
		echo 'sync config fail'
	else
		echo 'sync config sucess'
		pingConfigs
	fi
fi

# if [ $1 == "production" ]
# then
# 	syncConfig "conf/production"
# fi