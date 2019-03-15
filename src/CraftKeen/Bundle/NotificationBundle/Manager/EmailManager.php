<?php

namespace CraftKeen\Bundle\NotificationBundle\Manager;

use CraftKeen\Bundle\NotificationBundle\Model\EmailParametersBag;
use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\UserBundle\Entity\User;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Templating\EngineInterface;

class EmailManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var EngineInterface */
    protected $templating;

    /** @var \Swift_Mailer */
    protected $mailer;

    /** @var EmailParametersBag */
    protected $parametersBag;

    /**
     * @param EngineInterface $templating
     * @param \Swift_Mailer $mailer
     * @param EmailParametersBag $parametersBag
     */
    public function __construct(
        EngineInterface $templating,
        \Swift_Mailer $mailer,
        EmailParametersBag $parametersBag
    ) {
        $this->templating = $templating;
        $this->mailer = $mailer;
        $this->parametersBag = $parametersBag;
    }

    /**
     * @param Inbox $inbox
     * @param null $template
     * @param array $templateParams
     *
     * @return int
     */
    public function sendEmail(Inbox $inbox, $template = null, array $templateParams = [])
    {
        /** @var User $recipient */
        $recipient = $inbox->getRecipient();
        $body = $inbox->getMessage();
        $subject = $inbox->getSubject();

        if ($template) {
            $body = $this->templating->render($template, $templateParams);
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom(
                $this->parametersBag->getFcrPropertyCtaEmailFrom(),
                $this->parametersBag->getFcrPropertyCtaEmailLabel()
            )
            ->setTo($recipient->getEmail())
            ->setReplyTo($recipient->getEmail())
            ->setBody($body, 'text/html');
        $status = $this->mailer->send($message);
        if ($status) {
            $this->logger->debug(sprintf(
                'Email message "%s" successfully sent to "%s"',
                $subject,
                $recipient->getEmail()
            ));
        } else {
            $this->logger->error(sprintf(
                'Email message "%s" failed sent to "%s"',
                $subject,
                $recipient->getEmail()
            ));
        }

        return $status;
    }
}
