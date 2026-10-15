<?php date_default_timezone_set('Europe/London'); ?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js" <?php if (get_field('schema_page_type')): ?><?php the_field('schema_page_type'); ?><?php endif; ?>>

<head>
    <!-- do -->
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('set', 'developer_id.dY2E1Nz', true);
    </script>

    <!-- Complianz consent bridge for GTM Consent Mode -->
    <script>
        (function() {
            var revokeListeners = [],
                consentListeners = [];
            window.addRevokeListener = function(cb) {
                revokeListeners.push(cb);
            };
            window.addConsentUpdateListener = function(cb) {
                consentListeners.push(cb);
            };

            document.addEventListener('cmplz_revoke', function() {
                cmplz_set_cookie('cmplz_consent_mode', 'revoked', false);
                revokeListeners.forEach(function(cb) {
                    cb();
                });
            });

            document.addEventListener('cmplz_fire_categories', function(e) {
                var c = e.detail.categories;
                var m = cmplz_in_array('marketing', c) ? 'granted' : 'denied';
                var consent = {
                    security_storage: 'granted',
                    functionality_storage: 'granted',
                    personalization_storage: cmplz_in_array('preferences', c) ? 'granted' : 'denied',
                    analytics_storage: cmplz_in_array('statistics', c) ? 'granted' : 'denied',
                    ad_storage: m,
                    ad_user_data: m,
                    ad_personalization: m
                };
                var granted = Object.keys(consent).filter(function(k) {
                    return consent[k] === 'granted';
                });
                cmplz_set_cookie('cmplz_consent_mode', granted.join(','), false);
                consentListeners.forEach(function(cb) {
                    cb(consent);
                });
            });
        })();
    </script>
    <!-- End Complianz consent bridge -->
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PS7GDGZ');
    </script>
    <!-- End Google Tag Manager -->

    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(); ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="https://www.divorce-online.co.uk/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://www.divorce-online.co.uk/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.divorce-online.co.uk/favicon-16x16.png">
    <link rel="manifest" href="https://www.divorce-online.co.uk/site.webmanifest">
    <link rel="mask-icon" href="https://www.divorce-online.co.uk/safari-pinned-tab.svg" color="#bd0202">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!--<link href="<?php echo get_template_directory_uri(); ?>/img/swoosh-square.png" rel="shortcut icon">-->
    <link rel='preconnect' href='//www.google-analytics.com' />

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">

    <script id="usercentrics-cmp" src="https://web.cmp.usercentrics.eu/ui/loader.js" data-settings-id="9zTok7iDaSL70b" async></script>

    <!-- Microsoft Clarity -->
    <script type="text/javascript">
        (function(c, l, a, r, i, t, y) {
            c[a] = c[a] || function() {
                (c[a].q = c[a].q || []).push(arguments)
            };
            t = l.createElement(r);
            t.async = 1;
            t.src = "https://www.clarity.ms/tag/" + i;
            y = l.getElementsByTagName(r)[0];
            y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", "g84am2xpzp");
    </script>

    <!-- Linked in -->
    <script type="text/javascript">
        _linkedin_partner_id = "7537282";
        window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
        window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    </script>
    <script type="text/javascript">
        (function(l) {
            if (!l) {
                window.lintrk = function(a, b) {
                    window.lintrk.q.push([a, b])
                };
                window.lintrk.q = []
            }
            var s = document.getElementsByTagName("script")[0];
            var b = document.createElement("script");
            b.type = "text/javascript";
            b.async = true;
            b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
            s.parentNode.insertBefore(b, s);
        })(window.lintrk);
    </script> <noscript> <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=7537282&fmt=gif" /> </noscript>

    <script>
        (function(w, d, t, r, u) {
            var f, n, i;
            w[u] = w[u] || [], f = function() {
                    var o = {
                        ti: "26015287",
                        enableAutoSpaTracking: true
                    };
                    o.q = w[u], w[u] = new UET(o), w[u].push("pageLoad")
                },
                n = d.createElement(t), n.src = r, n.async = 1, n.onload = n.onreadystatechange = function() {
                    var s = this.readyState;
                    s && s !== "loaded" && s !== "complete" || (f(), n.onload = n.onreadystatechange = null)
                },
                i = d.getElementsByTagName(t)[0], i.parentNode.insertBefore(n, i)
        })
        (window, document, "script", "//bat.bing.com/bat.js", "uetq");
    </script>

    <?php wp_head(); ?>

    <?php if (isWithinTimeRange() === false) : ?>

        <!-- WhatConverts -->
        <script>
            var $wc_load = function(a) {
                    return JSON.parse(JSON.stringify(a))
                },
                $wc_leads = $wc_leads || {
                    doc: {
                        url: $wc_load(document.URL),
                        ref: $wc_load(document.referrer),
                        search: $wc_load(location.search),
                        hash: $wc_load(location.hash)
                    }
                };
        </script>
        <script src="//s.ksrndkehqnwntyxlhgto.com/163469.js"></script>

        <!-- LiveChat -->
        <script type="text/javascript">
            window.__lc = window.__lc || {};
            window.__lc.license = 5033341;
            window.__lc.chat_between_groups = false;

            window.__lc.params = [{
                name: 'wc_source',
                value: oqhaqzwnbjgcpycneryg('wc_client')
            }];

            (function() {
                var lc = document.createElement("script");
                lc.type = "text/javascript";
                lc.async = true;
                lc.src =
                    (document.location.protocol === "https:" ? "https://" : "http://") +
                    "cdn.livechatinc.com/tracking.js";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(lc, s);
            })();
        </script>


    <?php else : ?>
        <script src="https://cdn.customgpt.ai/js/chat.js"></script>
        <script>
            window.onload = function() {
                CustomGPT.init({
                    p_id: "1573",
                    p_key: "cc1ac77a637005616769973011176784"
                });
            };
        </script>
    <?php endif; ?>

    <link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">

</head>

<body <?php body_class(); ?>>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PS7GDGZ"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- wrapper -->
    <div class="wrapper">


        <div class="sg-header">
            <!-- Top Row -->
            <div class="header-top">
                <div class="header-container">
                    <!-- Logo Section -->
                    <div class="logo-section">
                        <a href="https://www.divorce-online.co.uk/"><img src="<?php echo get_template_directory_uri(); ?>/img/dol-logo-25.png" alt="Logo" class="logo-img" height="48" width="509"></a>
                    </div>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Call Us -->
                        <a href="tel:01793384029" class="call-us-link">
                            Call us on: <span class="phone-number">01793 384029</span>
                        </a>

                        <!-- Separator -->
                        <div class="header-separator"></div>

                        <!-- Search Icon -->
                        <div class="search-container">
                            <svg class="search-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span class="search-text">Search</span>
                        </div>

                        <!-- Request Callback Button -->
                        <a href="https://www.divorce-online.co.uk/divorce/which-service/" class="header-btn contact-btn">Get Started Online</a>

                        <!-- Case Login Button -->
                        <a href="#callback-request" class="header-btn existing-clients-btn">Book a Free Callback</a>

                        <!-- Find My Service Button (Mobile) -->
                        <a href="https://www.divorce-online.co.uk/divorce/which-service/" class="header-btn find-service-btn">Find My Service</a>

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Callback Row -->
            <div class="mobile-callback-row">
                <div class="header-container">
                    <a href="#callback-request" class="mobile-callback-link">Book Your Free Callback</a>
                </div>
            </div>

            <?php
            // Header nav: use TDT Menu Settings when plugin active, else current values as defaults
            $header_nav_links = function_exists('tdt_menu_links') ? array(
                'header_os_divorce'   => tdt_menu_links('header_os_divorce'),
                'header_os_financial' => tdt_menu_links('header_os_financial'),
                'header_os_family'    => tdt_menu_links('header_os_family'),
                'header_getting_started' => tdt_menu_links('header_getting_started'),
                'header_compare_us'   => tdt_menu_links('header_compare_us'),
                'header_fees_prices'  => tdt_menu_links('header_fees_prices'),
                'header_advice_help'  => tdt_menu_links('header_advice_help'),
                'header_contact'      => tdt_menu_links('header_contact'),
            ) : array(
                'header_os_divorce'   => array(array('text' => 'Managed Divorce Service', 'url' => 'https://www.divorce-online.co.uk/divorce-services/managed-divorce-services/managed-divorce/'), array('text' => 'Clean Break Divorce Service', 'url' => 'https://www.divorce-online.co.uk/divorce-services/managed-divorce-services/managed-divorce-and-consent-order/')),
                'header_os_financial' => array(array('text' => 'Fixed Fee Consent Order', 'url' => 'https://www.divorce-online.co.uk/divorce-services/consent-order-services/clean-break-consent-order-service/'), array('text' => 'Clean Break Order Online', 'url' => 'https://www.divorce-online.co.uk/divorce-services/consent-order-services/clean-break-consent-order-service/'), array('text' => 'Solicitor Consent Order', 'url' => 'https://www.divorce-online.co.uk/divorce-services/consent-order-services/managed-clean-break-consent-order-service/')),
                'header_os_family'    => array(array('text' => 'Prenuptial Agreement Online', 'url' => 'https://www.divorce-online.co.uk/family-law/prenuptial-agreement-service/'), array('text' => 'Postnuptial Agreement Online', 'url' => 'https://www.divorce-online.co.uk/family-law/postnuptial-agreement-service/'), array('text' => 'Separation Agreement Online', 'url' => 'https://www.divorce-online.co.uk/family-law/separation-agreement-service/'), array('text' => 'Cohabitation Agreement Online', 'url' => 'https://www.divorce-online.co.uk/family-law/cohabitation-agreement-service/')),
                'header_getting_started' => array(array('text' => 'Our Story', 'url' => 'https://www.divorce-online.co.uk/about-us/'), array('text' => 'Our Reviews', 'url' => 'https://www.divorce-online.co.uk/reviews/'), array('text' => 'How it Works', 'url' => 'https://www.divorce-online.co.uk/how-it-works/'), array('text' => 'Find A Service', 'url' => 'https://www.divorce-online.co.uk/divorce/which-service/')),
                'header_compare_us'   => array(array('text' => 'Divorce-Online Vs Local Solicitors', 'url' => 'https://www.divorce-online.co.uk/divorce-online-vs-high-street-solicitors/'), array('text' => 'Divorce-Online Vs Do It Yourself', 'url' => 'https://www.divorce-online.co.uk/divorce-online-vs-gov-uk/'), array('text' => 'Divorce-Online Vs Online Services', 'url' => 'https://www.divorce-online.co.uk/quickie-divorce-comparison/')),
                'header_fees_prices'  => array(array('text' => 'Fees & Prices', 'url' => 'https://www.divorce-online.co.uk/divorce-services/')),
                'header_advice_help'  => array(array('text' => 'Divorce FAQs', 'url' => 'https://www.divorce-online.co.uk/divorce/divorce-faqs/'), array('text' => 'Divorce advice', 'url' => 'https://www.divorce-online.co.uk/divorce/'), array('text' => 'Divorce finances advice', 'url' => 'https://www.divorce-online.co.uk/finances/'), array('text' => 'Divorce blog', 'url' => 'https://www.divorce-online.co.uk/blog/'), array('text' => 'Court Fee calculator', 'url' => 'https://www.divorce-online.co.uk/court-fee-exemption-and-remission-calculator/'), array('text' => 'Divorce Settlement calculator', 'url' => 'https://www.divorce-online.co.uk/divorce-settlement-calculator/')),
                'header_contact'      => array(array('text' => 'Contact Us', 'url' => 'https://www.divorce-online.co.uk/contact-us/')),
            );

            $mobile_nav_data = array(
                'our_services' => array(
                    'title'    => 'Our Services',
                    'panel_id' => 'our-services',
                    'sections' => array(
                        array(
                            'heading' => 'Divorce Services',
                            'links'   => $header_nav_links['header_os_divorce'],
                        ),
                        array(
                            'heading' => 'Financial Orders',
                            'links'   => $header_nav_links['header_os_financial'],
                        ),
                        array(
                            'heading' => 'Family Law',
                            'links'   => $header_nav_links['header_os_family'],
                        ),
                    ),
                ),
                'getting_started' => array(
                    'title'    => 'Getting Started',
                    'panel_id' => 'getting-started',
                    'sections' => array(
                        array(
                            'heading' => '',
                            'links'   => $header_nav_links['header_getting_started'],
                        ),
                    ),
                ),
                'compare_us' => array(
                    'title'    => 'Compare Us',
                    'panel_id' => 'compare-us',
                    'sections' => array(
                        array(
                            'heading' => '',
                            'links'   => $header_nav_links['header_compare_us'],
                        ),
                    ),
                ),
                'advice_help' => array(
                    'title'    => 'Advice & Help',
                    'panel_id' => 'advice-help',
                    'sections' => array(
                        array(
                            'heading' => '',
                            'links'   => $header_nav_links['header_advice_help'],
                        ),
                    ),
                ),
                'fees_prices' => $header_nav_links['header_fees_prices'],
                'contact'     => $header_nav_links['header_contact'],
            );
            ?>
            <script>
                window.headerNavData = <?php echo wp_json_encode($mobile_nav_data); ?>;
            </script>
            <!-- Bottom Row - Navigation -->
            <div class="header-nav">
                <div class="header-container">
                    <ul class="nav-links">
                        <li class="has-mega-dropdown">
                            <a href="" class="dropdown-trigger">Our Services</a>
                            <div class="mega-dropdown">
                                <div class="mega-dropdown-content">
                                    <h2 class="mega-dropdown-title">Our Services</h2>
                                    <div class="mega-dropdown-column services">
                                        <span class="mega-dropdown-column-heading">Divorce Services</span>
                                        <?php foreach ($header_nav_links['header_os_divorce'] as $link) : if (! empty($link['url'])) : ?><a href="<?php echo esc_url($link['url']); ?>" class="mega-dropdown-item"><?php echo esc_html($link['text']); ?></a><?php endif;
                                                                                                                                                                                                                                                        endforeach; ?>
                                    </div>
                                    <div class="mega-dropdown-column services">
                                        <span class="mega-dropdown-column-heading">Financial Orders</span>
                                        <?php foreach ($header_nav_links['header_os_financial'] as $link) : if (! empty($link['url'])) : ?><a href="<?php echo esc_url($link['url']); ?>" class="mega-dropdown-item"><?php echo esc_html($link['text']); ?></a><?php endif;
                                                                                                                                                                                                                                                        endforeach; ?>
                                    </div>
                                    <div class="mega-dropdown-column services">
                                        <span class="mega-dropdown-column-heading">Family Law</span>
                                        <?php foreach ($header_nav_links['header_os_family'] as $link) : if (! empty($link['url'])) : ?><a href="<?php echo esc_url($link['url']); ?>" class="mega-dropdown-item"><?php echo esc_html($link['text']); ?></a><?php endif;
                                                                                                                                                                                                                                                    endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="has-dropdown">
                            <a href="" class="dropdown-trigger">Getting Started</a>
                            <ul class="dropdown">
                                <?php foreach ($header_nav_links['header_getting_started'] as $link) : if (! empty($link['url'])) : ?><li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['text']); ?></a></li><?php endif;
                                                                                                                                                                                                                                endforeach; ?>
                            </ul>
                        </li>
                        <li class="has-dropdown">
                            <a href="" class="dropdown-trigger">Compare Us</a>
                            <ul class="dropdown">
                                <?php foreach ($header_nav_links['header_compare_us'] as $link) : if (! empty($link['url'])) : ?><li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['text']); ?></a></li><?php endif;
                                                                                                                                                                                                                            endforeach; ?>
                            </ul>
                        </li>
                        <?php $fp = $header_nav_links['header_fees_prices'];
                        if (! empty($fp[0]['url'])) : ?><li><a href="<?php echo esc_url($fp[0]['url']); ?>"><?php echo esc_html($fp[0]['text']); ?></a></li><?php endif; ?>
                        <li class="has-dropdown">
                            <a href="" class="dropdown-trigger">Advice & Help</a>
                            <ul class="dropdown">
                                <?php foreach ($header_nav_links['header_advice_help'] as $link) : if (! empty($link['url'])) : ?><li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['text']); ?></a></li><?php endif;
                                                                                                                                                                                                                            endforeach; ?>
                            </ul>
                        </li>
                        <?php $cu = $header_nav_links['header_contact'];
                        if (! empty($cu[0]['url'])) : ?><li><a href="<?php echo esc_url($cu[0]['url']); ?>"><?php echo esc_html($cu[0]['text']); ?></a></li><?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Mobile Navigation Overlay -->
            <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

            <!-- Mobile Navigation Menu -->
            <div class="mobile-nav-menu" id="mobileNavMenu"></div>
        </div>

        <!-- Search Popup -->
        <div id="search-popup" class="search-popup">
            <div class="search-popup-overlay"></div>
            <div class="search-popup-inner">
                <div class="search-popup-logo">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/dol-logo-25.png" alt="Logo" height="48" width="509">
                </div>
                <div class="close-popup-icon" id="close-search">&times;</div>
                <div class="search-popup-content">
                    <h2>Search our website</h2>
                    <div class="search-popup-form">
                        <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                            <div style="position: relative; width: 100%; text-align: center;">
                                <svg class="search-input-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <input type="search" name="s" placeholder="What are you looking for?" autocomplete="off">
                            </div>
                        </form>
                    </div>
                    <button type="submit" class="search-submit-btn" onclick="document.querySelector('body .search-popup-form form').submit();">Search</button>
                    <div class="return-to-website" onclick="closeSearchPopup()">Return to website</div>
                </div>
            </div>
        </div>

        <script>
            const navData = window.headerNavData || {};
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNavMenu = document.getElementById('mobileNavMenu');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const desktopNav = document.querySelector('body .sg-header .header-nav');
            const desktopNavMarker = document.createComment('desktop-nav-marker');
            let desktopNavDetached = false;
            let isMobileNavBuilt = false;

            function isMobileViewport() {
                return window.innerWidth <= 1024;
            }

            function sanitizeLinks(links) {
                if (!Array.isArray(links)) {
                    return [];
                }
                return links.filter(link => link && link.url && link.text);
            }

            function escapeHtml(value) {
                return String(value || '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function renderMobileLinkList(links) {
                return sanitizeLinks(links)
                    .map(link => `<a href="${escapeHtml(link.url)}">${escapeHtml(link.text)}</a>`)
                    .join('');
            }

            function renderMobileSection(section) {
                if (!section || !sanitizeLinks(section.links).length) {
                    return '';
                }
                const headingMarkup = section.heading ? `<h3>${escapeHtml(section.heading)}</h3>` : '';
                return `<div class="submenu-section">${headingMarkup}${renderMobileLinkList(section.links)}</div>`;
            }

            function renderMobilePanel(panel) {
                if (!panel || !panel.panel_id) {
                    return '';
                }
                const sections = Array.isArray(panel.sections) ? panel.sections.map(renderMobileSection).join('') : '';
                return `
						<div class="mobile-nav-panel" id="${escapeHtml(panel.panel_id)}-panel">
							<div class="mobile-nav-panel-header">
								<button class="mobile-nav-back-btn" data-back="main">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									Back
								</button>
							</div>
							<div class="mobile-nav-panel-content">
								<a href="" class="mobile-nav-panel-title non-clickable">${escapeHtml(panel.title)}</a>
								${sections}
							</div>
						</div>
					`;
            }

            function buildMobileNavigationMarkup() {
                const feesLink = sanitizeLinks(navData.fees_prices)[0];
                const contactLink = sanitizeLinks(navData.contact)[0];

                const ourServices = navData.our_services || {};
                const gettingStarted = navData.getting_started || {};
                const compareUs = navData.compare_us || {};
                const adviceHelp = navData.advice_help || {};

                return `
						<div class="mobile-nav-content">
							<ul class="nav-links">
								<li class="has-submenu">
									<a href="" class="mobile-submenu-trigger" data-submenu="${escapeHtml(ourServices.panel_id || 'our-services')}">${escapeHtml(ourServices.title || 'Our Services')}</a>
									<button class="chevron-btn" data-submenu="${escapeHtml(ourServices.panel_id || 'our-services')}">
										<svg class="chevron-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 18L15 12L9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>
								</li>
								<li class="has-submenu">
									<a href="" class="mobile-submenu-trigger" data-submenu="${escapeHtml(gettingStarted.panel_id || 'getting-started')}">${escapeHtml(gettingStarted.title || 'Getting Started')}</a>
									<button class="chevron-btn" data-submenu="${escapeHtml(gettingStarted.panel_id || 'getting-started')}">
										<svg class="chevron-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 18L15 12L9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>
								</li>
								<li class="has-submenu">
									<a href="" class="mobile-submenu-trigger" data-submenu="${escapeHtml(compareUs.panel_id || 'compare-us')}">${escapeHtml(compareUs.title || 'Compare Us')}</a>
									<button class="chevron-btn" data-submenu="${escapeHtml(compareUs.panel_id || 'compare-us')}">
										<svg class="chevron-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 18L15 12L9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>
								</li>
								${feesLink ? `<li><a href="${escapeHtml(feesLink.url)}">${escapeHtml(feesLink.text)}</a></li>` : ''}
								<li class="has-submenu">
									<a href="" class="mobile-submenu-trigger" data-submenu="${escapeHtml(adviceHelp.panel_id || 'advice-help')}">${escapeHtml(adviceHelp.title || 'Advice & Help')}</a>
									<button class="chevron-btn" data-submenu="${escapeHtml(adviceHelp.panel_id || 'advice-help')}">
										<svg class="chevron-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M9 18L15 12L9 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										</svg>
									</button>
								</li>
								${contactLink ? `<li><a href="${escapeHtml(contactLink.url)}">${escapeHtml(contactLink.text)}</a></li>` : ''}
							</ul>
							<div class="mobile-nav-actions">
								<a href="https://www.divorce-track.com/" target="_blank" class="mobile-case-login">Case Login</a>
								<div class="search-container">
									<svg class="search-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<span class="search-text">Search</span>
								</div>
								<a href="#callback-request" class="header-btn contact-btn">Request Free Callback</a>
								<div class="trustpilot-widget" data-locale="en-GB" data-template-id="5419b6ffb0d04a076446a9af" data-businessunit-id="4bdc6dc1000064000505c1fd" data-style-height="20px" data-style-width="100%" data-theme="light">
									<a href="https://uk.trustpilot.com/review/www.divorce-online.co.uk" target="_blank" rel="noopener">Trustpilot</a>
								</div>
							</div>
						</div>
						${renderMobilePanel(ourServices)}
						${renderMobilePanel(gettingStarted)}
						${renderMobilePanel(compareUs)}
						${renderMobilePanel(adviceHelp)}
					`;
            }

            function buildMobileNavigation() {
                if (!mobileNavMenu || isMobileNavBuilt) {
                    return;
                }
                mobileNavMenu.innerHTML = buildMobileNavigationMarkup();
                isMobileNavBuilt = true;
            }

            function destroyMobileNavigation() {
                if (!mobileNavMenu || !isMobileNavBuilt) {
                    return;
                }
                mobileNavMenu.innerHTML = '';
                isMobileNavBuilt = false;
            }

            function hideAllMobilePanels() {
                if (!mobileNavMenu) {
                    return;
                }
                const panels = mobileNavMenu.querySelectorAll('.mobile-nav-panel');
                panels.forEach(panel => panel.classList.remove('active'));
            }

            function showMobilePanel(panelId) {
                if (!mobileNavMenu || !panelId) {
                    return;
                }
                hideAllMobilePanels();
                const panel = mobileNavMenu.querySelector(`#${panelId}-panel`);
                if (panel) {
                    panel.classList.add('active');
                }
            }

            function toggleMobileMenu() {
                if (!mobileNavMenu || !mobileMenuToggle || !mobileNavOverlay) {
                    return;
                }
                buildMobileNavigation();
                mobileMenuToggle.classList.toggle('active');
                mobileNavMenu.classList.toggle('active');
                mobileNavOverlay.classList.toggle('active');

                if (mobileNavMenu.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }

            function closeMobileMenu() {
                if (mobileMenuToggle) {
                    mobileMenuToggle.classList.remove('active');
                }
                if (mobileNavMenu) {
                    mobileNavMenu.classList.remove('active');
                }
                if (mobileNavOverlay) {
                    mobileNavOverlay.classList.remove('active');
                }
                hideAllMobilePanels();
                document.body.style.overflow = '';
            }

            function detachDesktopNavOnMobile() {
                if (!desktopNav || desktopNavDetached || !desktopNav.parentNode) {
                    return;
                }
                desktopNav.parentNode.replaceChild(desktopNavMarker, desktopNav);
                desktopNavDetached = true;
            }

            function restoreDesktopNavOnDesktop() {
                if (!desktopNav || !desktopNavDetached || !desktopNavMarker.parentNode) {
                    return;
                }
                desktopNavMarker.parentNode.replaceChild(desktopNav, desktopNavMarker);
                desktopNavDetached = false;
            }

            function syncNavigationByViewport() {
                if (isMobileViewport()) {
                    detachDesktopNavOnMobile();
                    buildMobileNavigation();
                } else {
                    closeMobileMenu();
                    destroyMobileNavigation();
                    restoreDesktopNavOnDesktop();
                }
            }

            function closeSearchPopup() {
                const popup = document.getElementById('search-popup');
                if (popup) {
                    popup.classList.remove('active');
                }
            }

            window.closeSearchPopup = closeSearchPopup;

            document.addEventListener('click', function(e) {
                const searchTrigger = e.target.closest('.search-container');
                if (searchTrigger) {
                    const popup = document.getElementById('search-popup');
                    if (popup) {
                        popup.classList.add('active');
                        setTimeout(() => {
                            const searchInput = popup.querySelector('input[type="search"]');
                            if (searchInput) {
                                searchInput.focus();
                            }
                        }, 100);
                    }

                    if (mobileNavMenu && mobileNavMenu.classList.contains('active')) {
                        closeMobileMenu();
                    }
                }

                const nonInteractive = e.target.closest('.dropdown-trigger, body .non-clickable');
                if (nonInteractive) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            }, true);

            const closeSearchButton = document.getElementById('close-search');
            if (closeSearchButton) {
                closeSearchButton.addEventListener('click', closeSearchPopup);
            }

            const searchOverlay = document.querySelector('body .search-popup-overlay');
            if (searchOverlay) {
                searchOverlay.addEventListener('click', closeSearchPopup);
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
            }

            if (mobileNavOverlay) {
                mobileNavOverlay.addEventListener('click', closeMobileMenu);
            }

            if (mobileNavMenu) {
                mobileNavMenu.addEventListener('click', function(e) {
                    const submenuTrigger = e.target.closest('.mobile-submenu-trigger, .chevron-btn');
                    if (submenuTrigger) {
                        e.preventDefault();
                        e.stopPropagation();
                        const submenuId = submenuTrigger.getAttribute('data-submenu');
                        showMobilePanel(submenuId);
                        return;
                    }

                    const backButton = e.target.closest('.mobile-nav-back-btn');
                    if (backButton) {
                        e.preventDefault();
                        hideAllMobilePanels();
                        return;
                    }

                    const clickedLink = e.target.closest('a');
                    if (!clickedLink) {
                        return;
                    }
                    if (clickedLink.classList.contains('mobile-submenu-trigger') || clickedLink.classList.contains('non-clickable')) {
                        return;
                    }
                    closeMobileMenu();
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeSearchPopup();
                    if (mobileNavMenu && mobileNavMenu.classList.contains('active')) {
                        closeMobileMenu();
                    }
                }
            });

            // Smooth scroll for anchor links (excluding callback requests which should open popups)
            document.querySelectorAll('a[href^="#"]:not([href="#callback-request"])').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Set mega dropdown width to match desktop nav-links container
            function setMegaDropdownWidth() {
                const navLinks = document.querySelector('body .sg-header .header-nav .nav-links');
                const megaDropdown = document.querySelector('body .sg-header .header-nav .mega-dropdown');

                if (navLinks && megaDropdown) {
                    const navLinksWidth = navLinks.offsetWidth;
                    megaDropdown.style.width = navLinksWidth + 'px';
                }
            }

            function handleViewportChanges() {
                syncNavigationByViewport();
                setMegaDropdownWidth();
            }

            window.addEventListener('load', handleViewportChanges);
            window.addEventListener('resize', handleViewportChanges);
            document.addEventListener('DOMContentLoaded', handleViewportChanges);
            handleViewportChanges();
        </script>

        <?php
        function isWithinTimeRange()
        {
            // uncomment this to enable the bot
            // return true; 
            return false;

            $currentDayOfWeek = date('N'); // 1 (Monday) to 7 (Sunday)
            $currentHour = date('G');      // 0 to 23
            $currentDate = date('Y-m-d');

            // Check if it's Monday to Thursday, between 5:00 PM and 9:00 AM, or a bank holiday
            if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 4) {
                if ($currentHour >= 17 || $currentHour < 9) {
                    return true;
                }
            }

            // Check if it's Friday, between 4:00 PM and 8:00 AM
            if ($currentDayOfWeek == 5) {
                if ($currentHour >= 16 || $currentHour < 8) {
                    return true;
                }
            }

            if ($currentDayOfWeek >= 1 && $currentDayOfWeek <= 5) {
                // Check if it's a bank holiday
                $bankHolidaysURL = 'https://www.gov.uk/bank-holidays/england-and-wales.ics';
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $bankHolidaysURL);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $bankHolidaysData = curl_exec($curl);
                curl_close($curl);

                // Extract the bank holiday dates from the data
                preg_match_all('/DTSTART;VALUE=DATE:(\d{8})/', $bankHolidaysData, $matches);
                $bankHolidays = $matches[1];

                if (in_array($currentDate, $bankHolidays)) {
                    return true; // Within the specified time range and a bank holiday
                }
            }


            // Check if it's Saturday or Sunday
            if ($currentDayOfWeek == 6 || $currentDayOfWeek == 7) {
                return true;
            }
            return false; // Outside the specified time range, not a bank holiday, and not a weekend
        }
        ?>