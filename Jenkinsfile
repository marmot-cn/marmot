node {
    stage 'checkout compose file'
    git([url: 'https://github.com/chloroplast1983/marmot.git', branch: 'master'])
    echo 'checkout'
    stage 'test'
    echo 'test'
    stage 'release sandbox'
    dir('deployment/sandbox') {
        sh 'rancher-compose --verbose -p marmot up -d --upgrade --batch-size 1 --interval "30000" --confirm-upgrade nginx-1'
    }
    echo 'release sandbox'
    stage 'release production'
    timeout(time:10, unit:'SECONDS') {
        input message:'Release Production ?', ok: 'Release'
    }
    echo 'release production'
}
