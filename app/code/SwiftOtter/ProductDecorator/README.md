# Basic Concepts:
* Product Decorator calculates charges that are applied on top of the product's calculated price. Thus,
tier pricing, specials, etc. still work.
* This is overbuilt as the merchant would like to allow for the customization of multiple locations.
The `PrintSpec` and `PriceRequest` both have the capability for multiple locations and products.
* To rebuild the original price, run: `bin/magento catalog:reindex:price [price]`

# Todo, still:
* Handle quote item quantity updates, deletions.
* Clean cart
* Add admin area interface to show what was ordered.
* Potential: add proofing system to allow customer to approve print specs.
* Reindex when updating print charge, etc.
