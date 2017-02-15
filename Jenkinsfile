node {
    stage '检出最新代码'
    git([credentialsId: '576f44ca-5da6-4dba-9e3f-a618ceab2623', url: 'https://github.com/chloroplast1983/marmot', branch: 'master'])
    echo 'checkout success'
    stage '初始化环境'
    dir('deployment/test') {
        sh 'sudo docker-compose -p marmot-test stop'
        sh 'sudo docker-compose -p marmot-test rm -v -f'
        sh 'sudo docker-compose -p marmot-test pull'
        sh 'sudo docker-compose -p marmot-test up -d'
    }
    stage '代码规范检测'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpcs'
    stage '代码复制黏贴检测'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpcpd ./Application'
    stage '单元测试'
    timeout(10) {
        waitUntil {
            def r = sh script: 'sudo docker logs marmot-mysql 2>/dev/null | grep \'Ready for start up\'', returnStatus: true
            return (r == 0)
        }
    }
    sh 'cat ./database/database.sql | sudo docker exec -i marmot-mysql /usr/bin/mysql -uroot -p123456'
    sh 'cat ./database/test.sql | sudo docker exec -i marmot-mysql /usr/bin/mysql -uroot -p123456'
    sh 'for sqlfile in `ls ./database/*.execute.sql`; do sed \'1 s/;/_test;/g\' $sqlfile | sudo docker exec -i marmot-mysql /usr/bin/mysql -uroot -p123456; done'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpunit'
    stage '发布候选版本'
    //
    sh 'sudo docker pull registry-internal.cn-hangzhou.aliyuncs.com/marmot/marmot-data-container'
    sh 'sudo docker tag $(sudo docker images |grep \'registry-internal.cn-hangzhou.aliyuncs.com/marmot/marmot-data-container\'|grep \'latest\'|awk \'{print $3}\') registry-internal.cn-hangzhou.aliyuncs.com/marmot/marmot-data-container:$(cat ./VERSION)'
    sh 'sudo docker push registry-internal.cn-hangzhou.aliyuncs.com/marmot/marmot-data-container:$(cat ./VERSION)'
    stage '清理环境'
    dir('deployment/test') {
        sh 'sudo docker-compose -p marmot-test stop'
        sh 'sudo docker-compose -p marmot-test rm -v -f'
    }
    stage '部署沙箱'
    sh 'sed -i "s/VERSION/$(cat VERSION)/g" deployment/sandbox/docker-compose.yml'
    dir('deployment/sandbox') {
        sh 'rancher-compose --url ${RANCHER_URL} --access-key ${RANCHER_ACCESS_KEY} --secret-key ${RANCHER_SECRET_KEY} --verbose -p marmot up -d --upgrade --confirm-upgrade service'
    }
    echo 'release sandbox success'
    stage '部署生产'
    sh 'echo $datacontainer'
    timeout(time:2, unit:'DAYS') {
        input message:'Release Production ?', ok: 'Release'
    }
    echo 'release production success'
}
