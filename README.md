poll-bundle
===========

Adds simple polls to your Symfony 2 project. Can be used anonymously or with an existing user store.

NOTE: this bundle is still in heavy development - do not use, it WILL set your cat on fire.

Example configuration:

```yaml
    userfriendly_poll:
        enable_anonymous_polling: false
        user_class_registered_polling: Acme\SomeBundle\Entity\User

```

TODO:

- add jQuery to requirements
- add default styling
- secure anonymous polling as much as possible
- use in several projects at once to iron out the kinks and contingencies

