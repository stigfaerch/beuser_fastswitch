<?php

declare(strict_types=1);

namespace JosefGlatz\BeuserFastswitch\Controller;

use JosefGlatz\BeuserFastswitch\Domain\Repository\BackendUserRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Backend\View\BackendViewFactory;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

#[Autoconfigure(public: true)]
class BackendController extends ActionController
{
    public function __construct(
        private readonly BackendUserRepository $backendUserRepository,
        private readonly BackendViewFactory $backendViewFactory,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     *
     * @noinspection PhpUnused
     */
    public function userLookupAction(ServerRequestInterface $request): ResponseInterface
    {
        $view = $this->backendViewFactory->create($request, ['josefglatz/beuser-fastswitch']);
        $params = $request->getQueryParams();

        if (!empty($params['search'])) {
            $userList = $this->findUserBySearchWord($params['search']);
        } else {
            $userList = $this->findUsers();
        }

        $view->assignMultiple(
            [
                'users' => $userList,
            ]
        );

        return new HtmlResponse($view->render('UserLookup.html'));
    }

    /**
     * @param string $search
     * @return QueryResultInterface
     */
    protected function findUserBySearchWord(string $search): QueryResultInterface
    {
        return $this->backendUserRepository->findByMultipleProperties($search);
    }

    /**
     * @return QueryResultInterface
     */
    protected function findUsers(): QueryResultInterface
    {
        return $this->backendUserRepository->findNonAdmins();
    }
}
