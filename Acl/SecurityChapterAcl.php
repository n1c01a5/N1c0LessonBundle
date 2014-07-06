<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\ChapterBundle\Model\ChapterInterface;
use N1c0\ChapterBundle\Model\SignedChapterInterface;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Symfony\Component\Security\Acl\Model\AclInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Implements ACL checking using the Symfony2 Security component
 */
class SecurityChapterAcl implements ChapterAclInterface
{
    /**
     * Used to retrieve ObjectIdentity instances for objects.
     *
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    protected $objectRetrieval;

    /**
     * The AclProvider.
     *
     * @var MutableAclProviderInterface
     */
    protected $aclProvider;

    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * The FQCN of the Chapter object.
     *
     * @var string
     */
    protected $chapterClass;

    /**
     * The Class OID for the Chapter object.
     *
     * @var ObjectIdentity
     */
    protected $oid;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface                 $securityContext
     * @param ObjectIdentityRetrievalStrategyInterface $objectRetrieval
     * @param MutableAclProviderInterface              $aclProvider
     * @param string                                   $chapterClass
     */
    public function __construct(SecurityContextInterface $securityContext,
                                ObjectIdentityRetrievalStrategyInterface $objectRetrieval,
                                MutableAclProviderInterface $aclProvider,
                                $chapterClass
    )
    {
        $this->objectRetrieval   = $objectRetrieval;
        $this->aclProvider       = $aclProvider;
        $this->securityContext   = $securityContext;
        $this->chapterClass      = $chapterClass;
        $this->oid               = new ObjectIdentity('class', $this->chapterClass);
    }

    /**
     * Checks if the Security token is allowed to create a new Chapter.
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted('CREATE', $this->oid);
    }

    /**
     * Checks if the Security token is allowed to view the specified Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canView(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted('VIEW', $chapter);
    }


    /**
     * Checks if the Security token is allowed to edit the specified Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canEdit(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted('EDIT', $chapter);
    }

    /**
     * Checks if the Security token is allowed to delete the specified Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return boolean
     */
    public function canDelete(ChapterInterface $chapter)
    {
        return $this->securityContext->isGranted('DELETE', $chapter);
    }

    /**
     * Sets the default object Acl entry for the supplied Chapter.
     *
     * @param  ChapterInterface $chapter
     * @return void
     */
    public function setDefaultAcl(ChapterInterface $chapter)
    {
        $objectIdentity = $this->objectRetrieval->getObjectIdentity($chapter);
        $acl = $this->aclProvider->createAcl($objectIdentity);

        if ($chapter instanceof SignedChapterInterface &&
            null !== $chapter->getAuthor()) {
            $securityIdentity = UserSecurityIdentity::fromAccount($chapter->getAuthor());
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        }

        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs default Acl entries for the Chapter class.
     *
     * This needs to be re-run whenever the Chapter class changes or is subclassed.
     *
     * @return void
     */
    public function installFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->chapterClass);

        try {
            $acl = $this->aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $exists) {
            return;
        }

        $this->doInstallFallbackAcl($acl, new MaskBuilder());
        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs the default Class Ace entries into the provided $acl object.
     *
     * Override this method in a subclass to change what permissions are defined.
     * Once this method has been overridden you need to run the
     * `fos:chapter:installAces --flush` command
     *
     * @param  AclInterface $acl
     * @param  MaskBuilder  $builder
     * @return void
     */
    protected function doInstallFallbackAcl(AclInterface $acl, MaskBuilder $builder)
    {
        $builder->add('iddqd');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_SUPER_ADMIN'), $builder->get());

        $builder->reset();
        $builder->add('view');
        $acl->insertClassAce(new RoleSecurityIdentity('IS_AUTHENTICATED_ANONYMOUSLY'), $builder->get());

        $builder->reset();
        $builder->add('create');
        $builder->add('view');
        $acl->insertClassAce(new RoleSecurityIdentity('ROLE_USER'), $builder->get());
    }

    /**
     * Removes fallback Acl entries for the Chapter class.
     *
     * This should be run when uninstalling the ChapterBundle, or when
     * the Class Acl entry end up corrupted.
     *
     * @return void
     */
    public function uninstallFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->chapterClass);
        $this->aclProvider->deleteAcl($oid);
    }
}

