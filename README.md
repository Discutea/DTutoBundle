# TutorialBundle for discutea.com and ircz.fr

Warning this bundle doesn't work! It's being built!

Tutorial Bundle for symfony 3

Install: 

1) Register bundle in kernel:

    // app/AppKernel.php
    new Discutea\DTutoBundle\DTutoBundle(),

2) Add routing:
    # app/config/routing.yml
    discutea_tutorial:
        resource: "@DTutoBundle/Resources/config/routing.yml"
        prefix:   /