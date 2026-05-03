<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f5f7; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%;">

                    {{-- Header: Co-branding --}}
                    <tr>
                        <td style="padding: 24px 32px; text-align: center; background-color: #ffffff; border-radius: 16px 16px 0 0; border-bottom: 1px solid #e5e7eb;">
                            @if($orgLogoUrl)
                                <img src="{{ $orgLogoUrl }}" alt="{{ $orgName }}" style="max-height: 48px; max-width: 200px; margin-bottom: 8px;" />
                                <br>
                                <span style="font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px;">via {{ config('app.name') }}</span>
                            @else
                                <span style="font-size: 20px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">{{ config('app.name') }}</span>
                            @endif
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding: 32px; background-color: #ffffff;">

                            <p style="font-size: 16px; color: #1e293b; margin: 0 0 20px;">{{ __('mail.greeting_simple') }}</p>

                            {{-- Invitation message --}}
                            <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 24px;">
                                <strong style="color: #1e293b;">{{ $senderName }}</strong>
                                @if($senderRole)
                                    <span style="color: #9ca3af;">({{ $senderRole }})</span>
                                @endif
                                {!! __('mail.invitation.line1_rich', ['organization' => '<strong style="color: #1e293b;">' . e($orgName) . '</strong>', 'app' => config('app.name')]) !!}
                            </p>

                            {{-- Role badge --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px; background-color: #f0f9ff; border-radius: 12px; border: 1px solid #bfdbfe;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding-right: 12px; vertical-align: middle;">
                                                    <div style="width: 40px; height: 40px; background-color: #dbeafe; border-radius: 10px; text-align: center; line-height: 40px; font-size: 18px;">
                                                        @if($roleKey === 'admin') &#128737; @elseif($roleKey === 'manager') &#128188; @else &#128100; @endif
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <span style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; display: block;">{{ __('mail.invitation.your_role') }}</span>
                                                    <span style="font-size: 16px; font-weight: 700; color: #1e40af;">{{ $roleLabel }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $acceptUrl }}" style="display: inline-block; padding: 14px 40px; background-color: #6366f1; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 12px; letter-spacing: 0.5px; text-transform: uppercase;">
                                            {{ __('mail.invitation.action') }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Code alternative --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 16px; background-color: #f8fafc; border-radius: 12px; text-align: center; border: 1px solid #e2e8f0;">
                                        <span style="font-size: 12px; color: #64748b; display: block; margin-bottom: 8px;">{{ __('mail.invitation.or_use_code') }}</span>
                                        <span style="font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: 4px; font-family: 'Courier New', monospace;">{{ $code }}</span>
                                        <span style="font-size: 11px; color: #9ca3af; display: block; margin-top: 8px;">{{ __('mail.invitation.line4') }}</span>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f8fafc; border-radius: 0 0 16px 16px; border-top: 1px solid #e5e7eb; text-align: center;">
                            @if($orgName && $orgName !== config('app.name'))
                                <p style="font-size: 12px; color: #64748b; margin: 0 0 4px;">
                                    <strong>{{ $orgName }}</strong>
                                    @if($orgWebsite) &middot; <a href="{{ $orgWebsite }}" style="color: #6366f1; text-decoration: none;">{{ parse_url($orgWebsite, PHP_URL_HOST) }}</a> @endif
                                </p>
                                @if($orgContactEmail)
                                    <p style="font-size: 11px; color: #9ca3af; margin: 0 0 12px;">{{ $orgContactEmail }} @if($orgContactPhone) &middot; {{ $orgContactPhone }} @endif</p>
                                @endif
                                <p style="font-size: 11px; color: #9ca3af; margin: 0;">{{ __('mail.powered_by') }} <strong>{{ config('app.name') }}</strong></p>
                            @else
                                <p style="font-size: 12px; color: #64748b; margin: 0 0 4px;">
                                    <strong>{{ config('app.name') }}</strong> &mdash; {{ __('mail.tagline') }}
                                </p>
                            @endif

                            @if($unsubscribeUrl)
                                <p style="font-size: 10px; color: #9ca3af; margin: 12px 0 0;">
                                    <a href="{{ $unsubscribeUrl }}" style="color: #9ca3af; text-decoration: underline;">{{ __('mail.unsubscribe') }}</a>
                                </p>
                            @endif
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
