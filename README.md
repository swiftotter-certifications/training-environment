![](pub/media/course-title.png)

# The Art of Ecommerce Debugging
### A course to embed super-problem-solving strategies into your workflow: turning you into a super developer.

Hey, I'm Joseph Maxwell, and I've learned a lot through my decade as an ecommerce developer.
It's my goal through this course to supercharge your capabilities as you build and fix websites.

---
#### I hate to say it, but...
This project is not a good example of the code that I  write. If you're looking to hire me to work on your project,
please don't take this as "the example" of my work. There is some super-good code in here. And there is
some code that's old. And there is some code that I've built to replicate, well (how do I say this), the less-than-quality
code that I've seen in the wild.

---

#### Reward:
If you find a bug in this project that looks like a good one to solve, please email me: joseph@swiftotter.com. If I
agree that it's good to enter into this course, I'll pay you $150. Or, if you work with me to build out the documentation
for solving this bug, I'll pay you $500 (then, I'll record and publish it) and credit your name.

This course contains *five* problems to solve:

* Project 1: The Unchangeable Name that's Quite the Pain
* Project 2: Pricing Calculation Creates Speculation
* Project 3: Cart that Falls Apart
* Project 4: Zebra Stripes on a Slider?
* Project 5: Checkout is Lights Out

#### Process:
This course is built on the idea of a walk-through. You and I sit down together to troubleshoot these bugs. As such,
there are a couple of _critical_ points:
* The actual solution is way less important than how we get there.
* I have dealt with each of these bugs, personally (current exception is #4 
  where one of my teammates solved this). The result is I try to strike a 
  balance between my full troubleshooting and just giving you the solution. 
  If you choose to look at solutions, that's fine (but not recommended!).
* If you are avoiding solutions, stay out of `app/code/Project`. There, I 
  just told you where to find it.
* Watch / read the replication, then try to solve yourself using the TAD 
  framework.

#### Support:
* [Email us](mailto:learning@swiftotter.com)
* [Chat with us](https://join.slack.com/t/certifications-hq/shared_invite/zt-dhfoqfqv-Gbs972TAuMnakJK8Q8GWpw)

### Getting Started

**Project Requirements**
* PHP 7.4 (I know..., but you need to be running it, so that's why I'm using it for this project)
* MySQL 5.7 or 8
* ElasticSearch
* Ideally: case-sensitive file system

### Overview
1. Clone project 
2. Create the database
3. Run `composer install`
4. Run `bin/magento setup:install`
5. Import the latest training data set
6. Run `bin/magento setup:upgrade`
7. Ready to go!

#### 1. Clone Project
```bash
git clone https://github.com/swiftotter-certifications/art-of-ecommerce-debugging debugging
```

The last parameter is _which directory_ you wish to insert the project. Remove this parameter
to insert into the current directory.

_Note #1:_ If you get an Access Denied error, make sure you have properly configured your computer 
to connect to GitHub. Feel free to [email us](mailto:learning@swiftotter.com) and we will help 
you PRONTO.

_Note #2_: if you are using a docker-based environment
(like [Warden](https://docs.warden.dev/environments/magento2.html)), you will want to ssh into
the environment and continue these instructions.

#### 2. Create the database
If you are using a docker-based development environment (like Warden), the database is already 
created. Make sure to configure the correct name in configuration below.

If you are using a MAMP/WAMP/LAMP-esque system, you will need to create the database. Log into 
MySQL and run:
```mysql
CREATE DATABASE aoed;
```

If you are not using root credentials locally, you will also want to assign privileges:
```mysql
GRANT ALL PRIVILEGES ON aoed.* to 'myuser'@'%';
FLUSH PRIVILEGES;
```

#### 3. Install Composer
```bash
cd debugging # or whatever directory the project is found in
composer install
```

This should _just work_. The most likely error you will receive is regarding Magento 
authentication. If this happens, log into the
[Magento Marketplace](https://marketplace.magento.com/) and get your Composer credentials there.

#### 4. Install Magento
```bash
bin/magento setup:install \
    --db-host=mysql \ #or 127.0.0.1
    --db-user=canthelpyouhere \
    --db-password=orhereeither \
    --amqp-host=rabbitmq \ # or 127.0.0.1
    --search-engine=elasticsearch7 \ # select elasticsearch version
    --elasticsearch-host=elasticsearch \ # or 127.0.0.1
    --admin-user=admin_username \
    --admin-password=admin_password \
    --admin-email="me@me.com" \
    --admin-firstname=Great \
    --admin-lastname=Developer
```

_Note:_ if you are using a docker-based development environment, use the appropriate host names (typically, it is `mysql`, `elasticsearch`, etc.).
Otherwise, use `127.0.0.1`.

#### 5. Load the latest training data set
```bash
./vendor/bin/driver run --environment=local-init import-s3
```

This uses [Driver](https://github.com/SwiftOtter/Driver) to fetch in the latest data.

_Known problem:_ At this point, your admin details will be reverted each time you reload. We are getting this
fixed.

#### 6. Upgrade Magento
```bash
bin/magento setup:upgrade --keep-generated
```

#### 7. That's it!
Pretty easy, isn't it? Let's get rocking some bugs!

### Refreshing with the latest data
```bash
./vendor/bin/driver run --environment=local-init import-s3
```

_Known problem:_ At this point, your admin details will be reverted each time you reload. We are getting this
fixed.

