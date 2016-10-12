node {
    stage 'checkout compose file'
    git([credentialsId: '576f44ca-5da6-4dba-9e3f-a618ceab2623', url: 'https://github.com/chloroplast1983/marmot', branch: 'master'])
    echo 'checkout success'
    stage 'build environment'
    dir('deployment/test') {
        sh 'sudo docker-compose up -d'
    }
    stage 'code style check'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpcs'
    stage 'code copy paste check'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpcpd ./Application'
    satge 'code unit test'
    sh 'sudo docker exec marmot-phpfpm vendor/bin/phpunit'
    stage 'clean environment'
    dir('deployment/test') {
        sh 'sudo docker-compose stop'
        sh 'sudo docker-compose rm -v'
    }
    stage 'release sandbox'
    dir('deployment/sandbox') {
        sh 'rancher-compose --url ${RANCHER_URL} --access-key ${RANCHER_ACCESS_KEY} --secret-key ${RANCHER_SECRET_KEY} --verbose -p marmot up -d --upgrade --confirm-upgrade service'
    }
    echo 'release sandbox success'
    stage 'release production'
    timeout(time:2, unit:'DAYS') {
        input message:'Release Production ?', ok: 'Release'
    }
    echo 'release production success'
}
