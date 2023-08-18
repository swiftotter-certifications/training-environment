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

## Setup

### System Requirements

* PHP 8
* MySQL 8 or MariaDB 10.6
* ElasticSearch 7.16 or OpenSearch 2.5
* Composer 2

### Composer Credentials

Make sure you've obtained Composer credentials from [commercemarketplace.adobe.com](https://commercemarketplace.adobe.com/).

Run the following on your local workstation to store your credentials globally:

```shell
composer global config http-basic.repo.magento.com <username> <password>
```

### Clone Project

```shell
git clone git@github.com:swiftotter-certifications/training-environment.git local/path/to/training-environment
```

Run all below commands from the root directory of the project.

### Warden

Our official recommendation for a local environment is the Docker-based [Warden](https://warden.dev/). See its official
documentation for instructions on installing the `warden` CLI tool.

The project comes with a pre-configured `.env` file to bootstrap a Warden environment that will load at the domain
app.training-environment.test.

Simply use `warden` to sign a certificate and spin up the environment.

```shell
warden sign-certificate training-environment.test
warden env up
```

For all subsequent instructions, the given commands assume you are using Warden with the unedited `.env`.

SSH into the main container before running all subsequent installation commands:

```shell
warden shell
```

If you do _not_ use Warden, you will need to create an initial empty database in MySQL and be ready with your connection
details for various services.

### Installation Commands

Install all Composer dependencies.

```shell
composer install
```

For the smoothest possible initial Magento installation, it's best to disable custom modules. Run the following:

```shell
bin/magento module:disable Magento_BundleSampleData \
    Magento_CatalogRuleSampleData \
    Magento_CatalogSampleData \
    Magento_CmsSampleData \
    Magento_ConfigurableSampleData \
    Magento_CustomerSampleData \
    Magento_DownloadableSampleData \
    Magento_GroupedProductSampleData \
    Magento_MsrpSampleData \
    Magento_OfflineShippingSampleData \
    Magento_ProductLinksSampleData \
    Magento_ReviewSampleData \
    Magento_SalesRuleSampleData \
    Magento_SalesSampleData \
    Magento_SwatchesSampleData \
    Magento_TaxSampleData \
    Magento_ThemeSampleData \
    Magento_WidgetSampleData \
    Magento_WishlistSampleData \
    Project_Bug1NameNotSaving \
    Project_Bug2CartDetailsThatDisappear \
    Project_Bug4SlideshowBroken \
    Project_Bug5CheckoutLightsOut \
    Project_Bug6CheckoutDoesntRespond \
    Project_Common \
    SwiftOtter_Catalog \
    SwiftOtter_CategoryAsProduct \
    SwiftOtter_Checkout \
    SwiftOtter_CliPerformance \
    SwiftOtter_Customer \
    SwiftOtter_DownloadProduct \
    SwiftOtter_EventQueue \
    SwiftOtter_GiftCard \
    SwiftOtter_GraphQL \
    SwiftOtter_GraphQLClient \
    SwiftOtter_HandlingFee \
    SwiftOtter_InventoryFilter \
    SwiftOtter_Mailchimp \
    SwiftOtter_OrderExport \
    SwiftOtter_PageBuilder \
    SwiftOtter_ProductDecorator \
    SwiftOtter_Repository \
    SwiftOtter_Teaching \
    SwiftOtter_Utils \
    Avalara_BaseProvider \
    ClassyLlama_AvaTax \
    Bold_OrderComment \
    Fooman_EmailAttachments \
    MageMojo_Cron \
    MagePal_Core \
    MagePal_GmailSmtpApp \
    Ebizmarts_MailChimp \
    MarkShust_DisableTwoFactorAuth
```

Then run the following installation command. If you are _not_ using Warden:

* Don't include the options for `amqp-*`, `session-save*`, `cache-backend*` or `page-cache*`.
* If you're using OpenSearch, use "opensearch" as the value of `search-engine` and provide `opensearch-*` options instead of
    `elasticsearch-*`.
* Provide your own connection details for `db-*` and `elasticsearch-`*.

```shell
bin/magento setup:install \
     --backend-frontname=backend \
     --amqp-host=rabbitmq \
     --amqp-port=5672 \
     --amqp-user=guest \
     --amqp-password=guest \
     --db-host=db \
     --db-name=magento \
     --db-user=magento \
     --db-password=magento \
     --search-engine=elasticsearch7 \
     --elasticsearch-host=elasticsearch \
     --elasticsearch-port=9200 \
     --elasticsearch-index-prefix=magento2 \
     --elasticsearch-enable-auth=0 \
     --elasticsearch-timeout=15 \
     --http-cache-hosts=varnish:80 \
     --session-save=redis \
     --session-save-redis-host=redis \
     --session-save-redis-port=6379 \
     --session-save-redis-db=2 \
     --session-save-redis-max-concurrency=20 \
     --cache-backend=redis \
     --cache-backend-redis-server=redis \
     --cache-backend-redis-db=0 \
     --cache-backend-redis-port=6379 \
     --page-cache=redis \
     --page-cache-redis-server=redis \
     --page-cache-redis-db=1 \
     --page-cache-redis-port=6379
```

**NOTE:** If you encounter an error related to finding the "default" connection, simply run the installation command a 
second time.

Run the following commands to lock key config settings and build indexes and cache. (If you're not using Warden, you will
need to supply your own values for the "base_url" settings.)

```shell
bin/magento config:set --lock-env web/unsecure/base_url \
     "https://${TRAEFIK_SUBDOMAIN}.${TRAEFIK_DOMAIN}/"

bin/magento config:set --lock-env web/secure/base_url \
    "https://${TRAEFIK_SUBDOMAIN}.${TRAEFIK_DOMAIN}/"

bin/magento config:set --lock-env web/secure/offloader_header X-Forwarded-Proto

bin/magento config:set --lock-env web/secure/use_in_frontend 1
bin/magento config:set --lock-env web/secure/use_in_adminhtml 1
bin/magento config:set --lock-env web/seo/use_rewrites 1

bin/magento config:set --lock-env system/full_page_cache/caching_application 2
bin/magento config:set --lock-env system/full_page_cache/ttl 604800

bin/magento config:set --lock-env catalog/search/enable_eav_indexer 1

bin/magento config:set --lock-env dev/static/sign 0


bin/magento deploy:mode:set -s developer
bin/magento cache:disable block_html full_page

bin/magento indexer:reindex
bin/magento cache:flush
```

Now enable the Magento sample data packages and run new data updates:

```shell
bin/magento module:enable Magento_BundleSampleData \
    Magento_CatalogRuleSampleData \
    Magento_CatalogSampleData \
    Magento_CmsSampleData \
    Magento_ConfigurableSampleData \
    Magento_CustomerSampleData \
    Magento_DownloadableSampleData \
    Magento_GroupedProductSampleData \
    Magento_MsrpSampleData \
    Magento_OfflineShippingSampleData \
    Magento_ProductLinksSampleData \
    Magento_ReviewSampleData \
    Magento_SalesRuleSampleData \
    Magento_SalesSampleData \
    Magento_SwatchesSampleData \
    Magento_TaxSampleData \
    Magento_ThemeSampleData \
    Magento_WidgetSampleData \
    Magento_WishlistSampleData
    
bin/magento setup:upgrade
```

And finally, enable all other custom modules and run data updates again:

```shell
bin/magento module:enable Project_Bug1NameNotSaving \
    Project_Bug2CartDetailsThatDisappear \
    Project_Bug4SlideshowBroken \
    Project_Bug5CheckoutLightsOut \
    Project_Bug6CheckoutDoesntRespond \
    Project_Common \
    SwiftOtter_Catalog \
    SwiftOtter_CategoryAsProduct \
    SwiftOtter_Checkout \
    SwiftOtter_CliPerformance \
    SwiftOtter_Customer \
    SwiftOtter_DownloadProduct \
    SwiftOtter_EventQueue \
    SwiftOtter_GiftCard \
    SwiftOtter_GraphQL \
    SwiftOtter_GraphQLClient \
    SwiftOtter_HandlingFee \
    SwiftOtter_InventoryFilter \
    SwiftOtter_Mailchimp \
    SwiftOtter_OrderExport \
    SwiftOtter_PageBuilder \
    SwiftOtter_ProductDecorator \
    SwiftOtter_Repository \
    SwiftOtter_Teaching \
    SwiftOtter_Utils \
    Avalara_BaseProvider \
    ClassyLlama_AvaTax \
    Bold_OrderComment \
    Fooman_EmailAttachments \
    MageMojo_Cron \
    MagePal_Core \
    MagePal_GmailSmtpApp \
    Ebizmarts_MailChimp \
    MarkShust_DisableTwoFactorAuth
    
    
bin/magento setup:upgrade
```

Then use config locking to disable admin user 2FA for your local environment, and run final indexing and cache cleaning.

```shell
bin/magento config:set --lock-env twofactorauth/general/enable 0

bin/magento indexer:reindex
bin/magento cache:flush
```

### Create an Admin User

As a final step, create an admin user. Run the following and answer the prompts.

```shell
bin/magento admin:user:create
```
