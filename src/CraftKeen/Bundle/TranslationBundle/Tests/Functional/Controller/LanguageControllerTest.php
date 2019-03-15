<?php

namespace CraftKeen\Bundle\TranslationBundle\Tests\Functional\Controller;

use Akuma\Component\Testing\TestCase\WebTestCase;

class LanguageControllerTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient([], ['PHP_AUTH_USER' => 'superadmin@email.lo', 'PHP_AUTH_PW' => '123',]);
    }

    public function testView()
    {
        $this->client->request('GET', $this->getUrl('craftkeen_translation_admin_language_index'));
        $result = $this->client->getResponse();
        self::assertEquals(200, $result->getStatusCode());
    }

    /**
     * Generates a URL or path for a specific route based on the given parameters.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     *
     * @return string
     */
    protected function getUrl($name, $parameters = [], $absolute = false)
    {
        return $this->getContainer()->get('router')->generate($name, $parameters, $absolute);
    }
}
