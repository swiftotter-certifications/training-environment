<?php
declare(strict_types=1);
/**
 * @by SwiftOtter, Inc. 10/23/21
 * @website https://swiftotter.com
 **/

namespace Project\Common\Setup\Patch\Data;


use Magento\Cms\Model\PageRepository;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateHomePage implements DataPatchInterface
{
    const HOME_CONTENT = <<<EOF
<style>#html-body [data-pb-style=FASSCK9],#html-body [data-pb-style=TXGWS4R]{background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=TXGWS4R]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top}#html-body [data-pb-style=FASSCK9]{background-position:center center}#html-body [data-pb-style=L2FL783]{border-radius:0;min-height:500px;background-color:transparent}#html-body [data-pb-style=SSD3WXM],#html-body [data-pb-style=VEQXAFR]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="TXGWS4R"><div data-content-type="banner" data-appearance="poster" data-show-button="never" data-show-overlay="never" data-element="main"><div data-element="empty_link"><div class="pagebuilder-banner-wrapper" data-background-images="{\&quot;desktop_image\&quot;:\&quot;{{media url=sample/camp-sample-1.jpeg}}\&quot;}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="wrapper" data-pb-style="FASSCK9"><div class="pagebuilder-overlay pagebuilder-poster-overlay" data-overlay-color="" data-element="overlay" data-pb-style="L2FL783"><div class="pagebuilder-poster-content"><div data-element="content"><p style="min-height: 500px; place-content: center; display: grid;"><strong><span style="font-size: 36px; color: #fff;">Time for a camping getaway.</span></strong></p></div></div></div></div></div></div></div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="SSD3WXM"><div class="home-carousel" data-content-type="products" data-appearance="carousel" data-autoplay="false" data-autoplay-speed="4000" data-infinite-loop="true" data-show-arrows="true" data-show-dots="true" data-carousel-mode="default" data-center-padding="90px" data-element="main">{{widget type="Magento\CatalogWidget\Block\Product\ProductsList" template="Magento_PageBuilder::catalog/product/widget/content/carousel.phtml" anchor_text="" id_path="" show_pager="0" products_count="11" condition_option="category_ids" condition_option_value="20" type_name="Catalog Products Carousel" conditions_encoded="^[`1`:^[`aggregator`:`all`,`new_child`:``,`type`:`Magento||CatalogWidget||Model||Rule||Condition||Combine`,`value`:`1`^],`1--1`:^[`operator`:`==`,`type`:`Magento||CatalogWidget||Model||Rule||Condition||Product`,`attribute`:`category_ids`,`value`:`20`^]^]" sort_order="position"}}</div></div></div><div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="VEQXAFR"></div></div>
EOF;

    private ModuleDataSetupInterface $moduleDataSetup;

    private PageRepository $pageRepository;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        PageRepository $pageRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->pageRepository = $pageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $home = $this->pageRepository->getById('home');

        $home->setContent(self::HOME_CONTENT);

        $this->pageRepository->save($home);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {

        return [];
    }
}
