node {
    stage 'checkout compose file'
    git([credentialsId: '576f44ca-5da6-4dba-9e3f-a618ceab2623', url: 'https://github.com/chloroplast1983/marmot', branch: 'master'])
    echo 'checkout'
    stage 'test'
    echo 'test'
    stage 'release sandbox'
    dir('deployment/sandbox') {
        sh 'rancher-compose --url ${RANCHER_URL} --access-key ${RANCHER_ACCESS_KEY} --secret-key ${RANCHER_SECRET_KEY} --verbose -p marmot up -d --upgrade --confirm-upgrade service'
    }
    echo 'release sandbox'
    stage 'release production'
    timeout(time:2, unit:'DAYS') {
        input message:'Release Production ?', ok: 'Release'
    }
    echo 'release production'
}
