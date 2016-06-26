<?php
namespace User\Controller;
use System\Classes\Controller;
use User\Model\User;
use User\Model\Bank;
use User\View\UserSerializer;
use User\View\BankSerializer;
// use Tobscure\JsonApi\Document;
// use Tobscure\JsonApi\Collection;
// use Tobscure\JsonApi\Parameters;
use Neomerx\JsonApi\Document\Error;
use \Neomerx\JsonApi\Document\Link;
use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
// use \Neomerx\Samples\JsonApi\Application\EncodeSamples; 
use \Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Factories\Factory;
use Neomerx\JsonApi\Http\Request;


class IndexController extends Controller{

	public function index(){

		// $request = $this->getRequest();

		// $psr7request = new Request( function () use ($request) {
		// 							    return $request->getMethod();
		// 							  },
		// 							  function ($name) use ($request) {
		// 						        return $request->getHeader($name);
		// 							  }, function () use ($request) {
		// 						        return $request->getQueryParams();
		// 							  });

		// $factory    = new Factory();
		// $parameters = $factory->createQueryParametersParser()->parse($psr7request);
		// echo '<pre>';
		// var_dump($parameters->getIncludePaths());
		// var_dump($parameters->getFieldSets());
		// var_dump($parameters->getSortParameters());
		// var_dump($parameters->getPaginationParameters());
		// var_dump($parameters->getFilteringParameters());
		// var_dump($parameters->getUnrecognizedParameters());
		// exit();

		$error = new Error(
		    'some-id',
		    new Link('about-link'),
		    'some-status',
		    'some-code',
		    'some-title',
		    'some-detail',
		    ['source' => 'data'],
		    ['some'   => 'meta']
		);

		echo Encoder::instance()->encodeError($error);
		return true;

		$userOne = new \User\Model\User();
		$userOne->setId(1);
		$userOne->setCellPhone('15202939435');
		$userOne->setNickName('nickName1');
		$bankOne = new \User\Model\Bank();
		$bankOne->id = 1;
		// $bankOne->name = 'bankOne';
		// $bankOne->time = 'time1';
	
		$bankTwo = new \User\Model\Bank();
		$bankTwo->id = 2;
		// $bankTwo->name = 'bankTwo';
		// $bankTwo->time = 'time2';
		$userOne->bank = array($bankTwo,$bankOne);

		$userTwo = new \User\Model\User();
		$userTwo->setId(2);
		$userTwo->setCellPhone('11111111111');
		$userTwo->setNickName('nickName2');	
		$bankThree = new \User\Model\Bank();
		$bankThree->id = 2;
		// $bankThree->name = 'bankThree';
		// $bankThree->time = 'time3';
		$userTwo->bank[] = $bankThree;

		$options = new EncodingParameters([
            // Paths to be included. Note 'posts.comments' will not be shown.
            // 'users'
        ], [
            // Attributes and relationships that should be shown
            'users'  => ['cellPhone','banks'],
        ]);

		$encoder = Encoder::instance([
		    User::class => UserSerializer::class,
		    Bank::class => BankSerializer::class,

		], new EncoderOptions(JSON_PRETTY_PRINT, 'http://example.com/api/v1'));
		echo '<pre>';
		// var_dump($userOne);exit();
		echo $encoder->encodeData(array($userOne,$userTwo),$options) . PHP_EOL;

		// var_dump($_GET);exit();
		// $parameters = new Parameters($_GET);
		// $fields = $parameters->getFields();
		// $include = $parameters->getInclude(['author', 'comments', 'comments.author']);
		// $sort = $parameters->getSort(['title', 'created']);
		// echo '<pre>';
		// var_dump($include);
		// echo '---';
		// var_dump($fields);
		// echo '<pre>';
		// var_dump($sort);
		// return true;
		// $userOne = new \User\Model\User();
		// $userOne->setId(1);
		// $userOne->setCellPhone('15202939435');
		// $userOne->setNickName('nickName1');
		// $bankOne = new \User\Model\Bank();
		// $bankOne->id = 1;
		// $bankOne->name = 'bankOne';
		// $bankOne->time = 'time1';
		// $userOne->bank[] = $bankOne;
		// $bankTwo = new \User\Model\Bank();
		// $bankTwo->id = 2;
		// $bankTwo->name = 'bankTwo';
		// $bankTwo->time = 'time2';
		// $userOne->bank[] = $bankTwo;

		// $userTwo = new \User\Model\User();
		// $userTwo->setId(2);
		// $userTwo->setCellPhone('11111111111');
		// $userTwo->setNickName('nickName2');	
		// $bankThree = new \User\Model\Bank();
		// $bankThree->id = 2;
		// // $bankThree->name = 'bankThree';
		// // $bankThree->time = 'time3';
		// $userTwo->bank[] = $bankThree;

		// $users = array($userOne,$userTwo);

		// $collection = new Collection($users, new \User\View\UserSerializer);
		// $collection->with(['bank']);
		// $document = new Document($collection);

		// $document->addMeta('total', count($users));
		// $document->addLink('self', 'http://example.com/api/users');

		// $this->render($document);
		// return true;
	}
}
