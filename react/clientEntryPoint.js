// import ReactOnRails from 'react-on-rails'
// import WidgetApp from './startup/WidgetAppClient'
// import BuilderApp from './startup/BuilderApp'
import ReactOnRails from 'react-on-rails'
import Text from './startup/TextClient'
import CTABox from './startup/CTABoxClient'
// import CTABoxFinance from './startup/CTABoxFinanceClient'
import Result from './startup/ResultClient'
import FeatureSlider from './startup/FeatureSliderClient'
import Title from './startup/TitleServer'
import Button from './startup/ButtonClient'
import Tabs from './startup/TabsClient'
import TeamTabs from './startup/TeamTabsClient'
import Accordion from './startup/AccordionClient'
import LinkIconList from './startup/LinkIconListClient'
// import FinanceBlock from './startup/FinanceBlockClient'
import SliderInfo from './startup/SliderInfoClient'
import Video from './startup/VideoClient'
import FAQ from './startup/FAQClient'
import PersonBaner from './startup/PersonBanerClient'
import PersonBlock from './startup/PersonBlockClient'
import ConferenceCall from './startup/ConferenceCallClient'
import HeadQuarters from './startup/HeadQuartersClient'
import OtherOffices from './startup/OtherOfficesClient'
import GovernanceTabs from './startup/GovernanceTabsClient'
import PressReleases from './startup/PressReleasesClient'
import LastPressReleases from './startup/LastPressReleasesClient'
import AnalystCoverage from './startup/AnalystCoverageClient'
import GoogleMap from './startup/GoogleMapClient'
import RichEditor from './startup/RichEditorClient'

/*export default () => {
    return (
        <BuilderApp />
    )
}*/
// ReactOnRails.register({ BuilderApp })
// ReactOnRails.register({ BuilderApp })

ReactOnRails.register({
    Text,
    CTABox,
    // CTABoxFinance,
    FeatureSlider,
    Result,
    Title,
    Button,
    Tabs,
    TeamTabs,
    Accordion,
    LinkIconList,
    Video,
    FAQ,
    // FinanceBlock,
    SliderInfo,
    Video,
    PersonBaner,
    PersonBlock,
    ConferenceCall,
    HeadQuarters,
    OtherOffices,
    GovernanceTabs,
    PressReleases,
    LastPressReleases,
    AnalystCoverage,
    GoogleMap,
    RichEditor
})