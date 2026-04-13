<?php

namespace Webkul\Web\Support;

/**
 * Hajj pilgrim registration — reads General → Site → Hajj auth (register) from core_config.
 */
final class HajjAuthRegisterSettings
{
    private const PREFIX = 'general.store.hajj_auth.register.';

    public static function metaDescription(): string
    {
        $v = core()->getConfigData(self::PREFIX.'meta_description');

        return filled($v) ? (string) $v : (string) trans('web::hajj_auth.signup-form.page-title');
    }

    public static function metaKeywords(): string
    {
        $v = core()->getConfigData(self::PREFIX.'meta_keywords');

        return filled($v) ? (string) $v : (string) trans('web::hajj_auth.signup-form.page-title');
    }

    public static function newsletterSubscriptionEnabled(): bool
    {
        return self::bool(self::PREFIX.'newsletter_subscription');
    }

    public static function gdprAgreementActive(): bool
    {
        return self::bool(self::PREFIX.'gdpr_enabled')
            && self::bool(self::PREFIX.'gdpr_agreement_enabled');
    }

    public static function gdprAgreementLabel(): string
    {
        $v = core()->getConfigData(self::PREFIX.'gdpr_agreement_label');

        return filled($v) ? (string) $v : (string) trans('web::hajj_auth.signup-form.gdpr-agreement-label');
    }

    public static function gdprAgreementContent(): ?string
    {
        $v = core()->getConfigData(self::PREFIX.'gdpr_agreement_content');

        return filled($v) ? (string) $v : null;
    }

    private static function bool(string $code): bool
    {
        return filter_var(core()->getConfigData($code), FILTER_VALIDATE_BOOLEAN);
    }
}
