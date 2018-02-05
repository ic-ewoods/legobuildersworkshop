# LEGO Builders Workshop

A simple site to contribute to our cause: Building with LEGO!


## Code Challenge

Actually, this is an effort at learning and demonstrating Stripe integration. Additionally, for the author it is an education in the current state of Drupal custom module development and is a foray into using [Lando](https://docs.devwithlando.io/recipes/drupal8.html) for local development.

## Local Environment Setup

Hopefully the work with Lando makes this super easy. It took some investment in getting around the details and the documentation of Lando to connect everything just right, but I think it's down to these steps:

#### 1. [Install Lando](https://docs.devwithlando.io/installation/installing.html)
Note that if you already have an up-to-date Docker you want to use the custom install and NOT let Lando install Docker.

#### 2. Git checkout
You know the drill:
```bash
git clone https://github.com/woodseowl/legobuildersworkshop.git lbw_donate
```

#### 3. Start up Lando
```bash
cd lbw_donate
lando start
```
That will take some time the first run. You're going to pull a whole bunch of docker images.

#### 4. Composer install
Do this from the repo directory.
There are two routes to take: inside Lando or with a local composer. While I love the fully containered delivery option:

```bash
lando composer install
```

directly running composer on my host OS is faster:

```bash
composer install
```

And either way will take a while the first time, of course.

#### 5. Install Drupal

Lando should have reported several URLs for where you can reach your local site, but I expect one of those will be here: http://lbw-donate.lndo.site/

Go there and complete the basic Drupal install. You can get the mysql details from the startup of your Lando instance, which are identical to the documented [Environment Variables](https://docs.devwithlando.io/tutorials/drupal8.html#environment-variables)

#### Further Reading

I grabbed a lot of details from Lando's [Killer Drupal 8 Workflow for Pantheon](https://github.com/lando/lando-pantheon-ci-workflow-example). Since I was already familiar with Pantheon + CI workflow and it had tooling support for testing, it seemed like a good place to begin. The [Lando Reference](https://github.com/lando/lando-pantheon-ci-workflow-example#lando-reference) section is particularly useful as a quick summary of commands that are useful.


## Donation Module Setup

I'm hoping this has been a simple cookbook process so far and that you now have a solid Drupal platform. If that's the case, there is a custom module that should be ready to be setup.

#### 1. Enable the module
Standard drush...

```bash
lando drush en lbw_donation
```

#### 2. Configure Stripe

You should have a Stripe account. Once you do, you should have [API test keys](https://dashboard.stripe.com/account/apikeys) and you can put in the public and secret test keys via the [Stripe settings](http://lbw-donate.lndo.site/admin/config/stripe) in Drupal. Don't worry with the web hook or the live keys for now.

#### 3. Behat test

I decided to build out a simplistic behat testing framework for this site. Honestly, what is there is really rudimentary and I have a lot more to learn. I've done lots with TDD and a little with BDD over the years but not in Drupal. So, this is not much of a test, but I wanted to point out that they are available:

```bash
cd tests
lando behat
```

And it is a great way to confirm that the module is installed successfully, that template files are getting loaded, and the site is functioning in general. So, if those all pass, you're good to go!


## Review

Most of what you want to see is in [/web/modules/custom/lbw_donation](https://github.com/woodseowl/legobuildersworkshop/tree/master/web/modules/custom/lbw_donation) as well as the main composer.json.

The donation form is reachable at http://lbw-donate.lndo.site/donate if you are using Lando.
