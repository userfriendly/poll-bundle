poll-bundle
===========

Adds simple polls to your Symfony 2 project. Can be used anonymously or with an existing user store.

NOTE: this bundle is still in heavy development - do not use, it WILL set your cat on fire.

If you are using an existing user storage, you need to add the following to your doctrine config:

```yaml
    doctrine:
        orm:
            resolve_target_entities:
                Userfriendly\Bundle\PollBundle\Model\UserInterface: Acme\SomeBundle\Entity\User

```

TODO: have the bundle do this by itself

```yaml
    # Doctrine Extensions
    stof_doctrine_extensions:
        orm:
            default:
                timestampable: true
                sluggable: true
                sortable: true
```

More TODOs:

- add configuration & routing
- add GUI things & controllers
- secure anonymous polling as much as possible

