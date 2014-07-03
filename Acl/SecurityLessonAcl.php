<?php

namespace N1c0\LessonBundle\Acl;

use N1c0\LessonBundle\Model\LessonInterface;
use N1c0\LessonBundle\Model\SignedLessonInterface;
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
class SecurityLessonAcl implements LessonAclInterface
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
     * The FQCN of the Lesson object.
     *
     * @var string
     */
    protected $lessonClass;

    /**
     * The Class OID for the Lesson object.
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
     * @param string                                   $lessonClass
     */
    public function __construct(SecurityContextInterface $securityContext,
                                ObjectIdentityRetrievalStrategyInterface $objectRetrieval,
                                MutableAclProviderInterface $aclProvider,
                                $lessonClass
    )
    {
        $this->objectRetrieval   = $objectRetrieval;
        $this->aclProvider       = $aclProvider;
        $this->securityContext   = $securityContext;
        $this->lessonClass      = $lessonClass;
        $this->oid               = new ObjectIdentity('class', $this->lessonClass);
    }

    /**
     * Checks if the Security token is allowed to create a new Lesson.
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted('CREATE', $this->oid);
    }

    /**
     * Checks if the Security token is allowed to view the specified Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canView(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted('VIEW', $lesson);
    }


    /**
     * Checks if the Security token is allowed to edit the specified Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canEdit(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted('EDIT', $lesson);
    }

    /**
     * Checks if the Security token is allowed to delete the specified Lesson.
     *
     * @param  LessonInterface $lesson
     * @return boolean
     */
    public function canDelete(LessonInterface $lesson)
    {
        return $this->securityContext->isGranted('DELETE', $lesson);
    }

    /**
     * Sets the default object Acl entry for the supplied Lesson.
     *
     * @param  LessonInterface $lesson
     * @return void
     */
    public function setDefaultAcl(LessonInterface $lesson)
    {
        $objectIdentity = $this->objectRetrieval->getObjectIdentity($lesson);
        $acl = $this->aclProvider->createAcl($objectIdentity);

        if ($lesson instanceof SignedLessonInterface &&
            null !== $lesson->getAuthor()) {
            $securityIdentity = UserSecurityIdentity::fromAccount($lesson->getAuthor());
            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        }

        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs default Acl entries for the Lesson class.
     *
     * This needs to be re-run whenever the Lesson class changes or is subclassed.
     *
     * @return void
     */
    public function installFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->lessonClass);

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
     * `fos:lesson:installAces --flush` command
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
     * Removes fallback Acl entries for the Lesson class.
     *
     * This should be run when uninstalling the LessonBundle, or when
     * the Class Acl entry end up corrupted.
     *
     * @return void
     */
    public function uninstallFallbackAcl()
    {
        $oid = new ObjectIdentity('class', $this->lessonClass);
        $this->aclProvider->deleteAcl($oid);
    }
}

