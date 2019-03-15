<?php

namespace CraftKeen\CMS\ThemeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/en-ca/themebundle")
     */
    public function indexAction()
    {
        $heroData = [
            'title' => 'Get To Know Us',
            'svg_button' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 352.4 352.5" style="enable-background:new 0 0 352.4 352.5;" xml:space="preserve">
            <style type="text/css">
                .st0{fill:#FFFFFF;}
            </style>
            <title>playbutton</title>
            <path class="st0" d="M262.2,172.5c2.3,1.3,3.1,4.3,1.8,6.6c-0.4,0.7-1,1.4-1.8,1.8l-65.1,37.6L132,256c-2.3,1.3-5.3,0.6-6.6-1.8
                c-0.4-0.7-0.7-1.6-0.7-2.4V101.6c0-2.7,2.2-4.8,4.8-4.9c0.9,0,1.7,0.2,2.4,0.7l65.1,37.6L262.2,172.5z"></path>
            <g>
                <path class="st0" d="M176.2,349C80.9,349,3.4,271.5,3.4,176.2C3.4,81,80.9,3.5,176.2,3.5S349,81,349,176.2
                    C349,271.5,271.5,349,176.2,349z M176.2,12.5c-90.3,0-163.8,73.5-163.8,163.8S85.9,340,176.2,340c90.3,0,163.8-73.5,163.8-163.8
                    S266.5,12.5,176.2,12.5z"></path>
            </g>
            </svg>',
            'link_video' => 'https://player.vimeo.com/video/196023530',
            'url_images' => '/bundles/craftkeencmstheme/FCR/assets/images/about_hero.jpg',
        ];

        return $this->render('CraftKeenCMSThemeBundle:FCR:Home/index.html.twig', [
            'heroData' => $heroData,
        ]);
    }

    /**
     * @Route("/en-ca/widgwork")
     */
    public function widgworkAction()
    {
        $heroData = [
            'title' => 'Get To Know Us',
            'svg_button' => '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 352.4 352.5" style="enable-background:new 0 0 352.4 352.5;" xml:space="preserve">
            <style type="text/css">
                .st0{fill:#FFFFFF;}
            </style>
            <title>playbutton</title>
            <path class="st0" d="M262.2,172.5c2.3,1.3,3.1,4.3,1.8,6.6c-0.4,0.7-1,1.4-1.8,1.8l-65.1,37.6L132,256c-2.3,1.3-5.3,0.6-6.6-1.8
                c-0.4-0.7-0.7-1.6-0.7-2.4V101.6c0-2.7,2.2-4.8,4.8-4.9c0.9,0,1.7,0.2,2.4,0.7l65.1,37.6L262.2,172.5z"></path>
            <g>
                <path class="st0" d="M176.2,349C80.9,349,3.4,271.5,3.4,176.2C3.4,81,80.9,3.5,176.2,3.5S349,81,349,176.2
                    C349,271.5,271.5,349,176.2,349z M176.2,12.5c-90.3,0-163.8,73.5-163.8,163.8S85.9,340,176.2,340c90.3,0,163.8-73.5,163.8-163.8
                    S266.5,12.5,176.2,12.5z"></path>
            </g>
            </svg>',
            'link_video' => 'https://player.vimeo.com/video/196023530',
            'url_images' => '/bundles/craftkeencmstheme/FCR/assets/images/about_hero.jpg',
        ];
        $WidgetCTABox = [
            [
                'title' => "Portfolio &amp; Leasing",
                'description' => "With a well-established presence across the Canadian retail landscape, First Capital Realty properties provide exceptional urban locations for Canada's leading retailers.",
                'image' => "/bundles/craftkeencmstheme/FCR/assets/images/home-cta1.jpg",
            ],
            [
                'title' => "Company Life",
                'description' => "Our people are our strength. That's why we work collectively to build a company culture similarly to when we build a new shopping centre, by focusing on community and excellence in everything we do.",
                'image' => "/bundles/craftkeencmstheme/FCR/assets/images/home-cta2.jpg",
            ],
            [
                'title' => "In the News",
                'description' => "We are a dynamic company in a dynamic industry. Follow the latest news and updates about First Capital Realty.",
                'image' => "/bundles/craftkeencmstheme/FCR/assets/images/home-cta3.jpg",
            ],
        ];
        $WidgetCTABoxFinance = [
            'time' => "05/15/2017 9:45am",
            'index' => "$19.93",
            'val_usd' => "$0.32",
            'val_percent' => '1.66%',
            'cls' => 'pink-arrow',
        ];
        $WidgetTabs = [
            'items' => [
                '0' => [
                    'title' => "tab 1",
                    'content' => "With a well-established presence across the Canadian retail landscape, First Capital Realty properties provide exceptional urban locations for Canada's leading retailers.",
                ],
                '1' => [
                    'title' => "Tab 2",
                    'content' => "Our people are our strength. That's why we work collectively to build a company culture similarly to when we build a new shopping centre, by focusing on community and excellence in everything we do.",
                ],
                '2' => [
                    'title' => "Tab 3",
                    'content' => "We are a dynamic company in a dynamic industry. Follow the latest news and updates about First Capital Realty.",
                ],

            ],
        ];
        $WidgetAccordion = [
            'config' => [
                'widgetClasses' => 'dark-blue-accordion',
            ],
            'items' => [
                '0' => [
                    'title' => "2017 ",
                    'content' => "With a well-established presence across the Canadian retail landscape, First Capital Realty properties provide exceptional urban locations for Canada's leading retailers.",
                ],
                '1' => [
                    'title' => "2018",
                    'content' => "Our people are our strength. That's why we work collectively to build a company culture similarly to when we build a new shopping centre, by focusing on community and excellence in everything we do.",
                ],
                '2' => [
                    'title' => "2019",
                    'content' => "We are a dynamic company in a dynamic industry. Follow the latest news and updates about First Capital Realty.",
                ],

            ],
        ];
        $WidgetPressReleases = [
            'config' => [
                'widgetClasses' => '',
            ],
            'items' => [
                '0' => [
                    'year' => "2017 ",
                    'elements' => [
                        '0' =>  [
                            'date' => 'May 09, 2017',
                            'href' => 'https://fcr.ca/ir/press-releases/details?id=287',
                            'file' => 'https://fcr.ca/uploads/press_releases/May-9-2017---FIRST-CAPITAL-REALTY-ANNOUNCES-FIRST-QUARTER-2017-RESULTS---FINAL.pdf',
                            'name' => 'First Capital Realty Announces First Quarter 2017 Results',
                        ]
                    ]
                ],
            ]
        ];
        $WidgetFinanceBlock = [
            'time' => "TSX:FCR As of 05/18/2017 4:57pm EDT",
            'index' => "$19.60",
            'val_usd' => "$0.32",
            'val_percent' => '1.61%',
            'config' => [
                'widgetClasses' => 'pink-arrow'
            ]
        ];
        $WidgetSliderInfo = [
            'config' => [
                'widgetClasses' => '',
            ],
            'items' => [
                '0' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide1.jpg",
                    'title' => "EMPLOYEE BENEFITS",
                    'description' => "At FCR, we ensure our financial renumeration is always very competitive. We also work hard to build a more complete compensation package. Here are some of our employee 'perks'",
                    'leftText' => "5",
                ],
                '1' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide2.png",
                    'title' => "EMPLOYEE SHARE PURCHASE PLAN (ESPP)",
                    'description' => "We believe members of our team should share in the success the company enjoys. To that end, FCR offers all full-time employees the opportunity to own shares of the company through a subsidized ESPP.",
                    'leftText' => "",
                ],
                '2' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide3.jpg",
                    'title' => "COMPANY RRSP",
                    'description' => "FCR offers all full-time employees a competitive RRSP match program.",
                    'leftText' => "",
                ],
                '3' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide4.png",
                    'title' => "TUITION REIMBURSEMENT",
                    'description' => "We embrace the importance of continuous learning and the development of skills and knowledge and provide a generous annual tuition reimbursement program.",
                    'leftText' => "",
                ],
                '4' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide5.png",
                    'title' => "EMPLOYEE REFERRAL PROGRAM",
                    'description' => "A key component to success is the ability to attract and retain the best people. That’s why we reward our employees with an ample cash bonus if their referral is hired.",
                    'leftText' => "",
                ],
                '5' => [
                    'picture' => "/bundles/craftkeencmstheme/FCR/assets/images/sliderinfo/slide6.jpg",
                    'title' => "WELLNESS SUBSIDY",
                    'description' => "All employees are encouraged to stay fit and live a healthy lifestyle. As such, we offer a wellness subsidy for health and wellness related expenses.",
                    'leftText' => "",
                ],
            ],
        ];

        $WidgetSliderInfo2 = [
            'config' => [
                'widgetClasses' => 'no-images-slider',
            ],
            'items' => [
                '0' => [
                    'picture' => "",
                    'title' => "ENERGY CONSUMPTION DECREASE",
                    'description' => "In 2015, energy consumption decreased by 8% compared to 2014.",
                    'leftText' => "8%",
                ],
                '1' => [
                    'picture' => "",
                    'title' => "MILLION SQ FT CERTIFIED LEED",
                    'description' => "As of December 31, 2015, 103 properties comprising 3.3 million square feet or 13% of the Company’s GLA were certified to LEED.",
                    'leftText' => "3.3",
                ],
                '2' => [
                    'picture' => "",
                    'title' => "MILLION SQ FT BOMA BEST",
                    'description' => "As of December 31, 2015, 104 properties comprising 9.5 million square feet or 39% of the Company’s GLA received BOMA BEST certification.",
                    'leftText' => "9.5",
                ],
            ],
        ];

        $WidgetPersonBaner = [
            'photo' => "/bundles/craftkeencmstheme/FCR/assets/images/content/Abt_CEO_Img.png",
            'name' => "ADAM E. PAUL",
            'description' => "President and Chief Executive Officer",
            'blockquote' => "Day to day our talented team executes our vision by creating and operating urban retail shopping centres and generating long term value for our stakeholders. Our track record in this regard is something we are very very proud of at First Capital Realty and is the main reason we are so optimistic as we look ahead.",
            "config" => "",
        ];

        $WidgetPersonBlock = [
            'config' => [
                'wrapperClass' => 'buffer-6-padding-bottom buffer-6-padding-top',
            ],
            'items' => [
                '0' => [
                    'photo' => "/bundles/craftkeencmstheme/FCR/assets/images/content/adam_paul.jpg",
                    'name' => "Adam E. Paul",
                    'position' => "President & Chief Executive Officer",
                    'description' => "President and Chief Executive Officer, First Capital Realty Inc. - Toronto, Ontario President & CEO of the Company.  Previously, a senior executive at Canadian Real Estate Investment Trust (“CREIT”), where he had direct responsibility for various aspects of CREIT’s business across Canada. Mr. Paul is a Chartered Professional Accountant, Chartered Accountant. Joined the Company and the Board in 2015.",
                ],
                '1' => [
                    'photo' => "/bundles/craftkeencmstheme/FCR/assets/images/content/kay-b.jpg",
                    'name' => "Kay Brekken",
                    'position' => "Executive Vice President & Chief Financial Officer",
                    'description' => "Joined the Company in 2014. Previously Executive Vice President and Chief Financial Officer of Indigo Books & Music, Inc. Ms. Brekken has over 20 years of North American financial leadership experience including public company reporting, strategic and operational planning, and debt and equity financing. Ms. Brekken holds a BBA from the University of Minnesota and an MBA from the University of Washington. Additionally, Ms. Brekken is a Certified Public Accountant.",
                ],
                '2' => [
                    'photo' => "/bundles/craftkeencmstheme/FCR/assets/images/content/jordan-robins.jpg",
                    'name' => "JORDAN ROBINS",
                    'position' => "Executive Vice President & Chief Operating Officer",
                    'description' => "Joined the Company in 2016. Previously, Senior Vice President, Planning and Development of RioCan REIT. Mr. Robins brings to the Company over 20 years of extensive experience and a proven track record in many facets of retail real estate including development, leasing and acquisitions. Mr. Robins holds a Master of Science degree in Facilities Management from the Pratt Institute.",
                ],
            ],
        ];

        return $this->render('CraftKeenCMSThemeBundle:FCR:Widgwork/index.html.twig', [
            'heroData' => $heroData,
            'WidgetCTABox' => $WidgetCTABox,
            'WidgetCTABoxFinance' => $WidgetCTABoxFinance,
            'WidgetTabs' => $WidgetTabs,
            'WidgetAccordion' => $WidgetAccordion,
            'WidgetFinanceBlock' => $WidgetFinanceBlock,
            'WidgetSliderInfo' => $WidgetSliderInfo,
            'WidgetSliderInfo2' => $WidgetSliderInfo2,
            'WidgetPersonBaner' => $WidgetPersonBaner,
            'WidgetPersonBlock' => $WidgetPersonBlock,
            'WidgetPressReleases' => $WidgetPressReleases

        ]);
    }
}
