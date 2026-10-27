<?php

namespace App\Enum;

enum CarStatus: string
{
    case AVAILABLE = 'available';
    case UNDER_OFFER = 'under_offer';
    case SOLD = 'sold';
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Available',
            self::UNDER_OFFER => 'Under Offer',
            self::SOLD => 'SOLD',
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PUBLISHED, self::AVAILABLE => 'badge bg-success',
            self::SOLD => 'badge bg-danger',
            self::DRAFT => 'badge bg-warning text-dark',
            self::UNDER_OFFER => 'badge text-white',
        };
    }

    public function badgeStyle(): string
    {
        return match ($this) {
            self::UNDER_OFFER => 'background-color: #fd7e14;',
            default => '',
        };
    }

    public function badgeIcon(): string
    {
        return match ($this) {
            self::DRAFT => 'ri-draft-line',
            self::SOLD => 'ri-close-circle-line',
            self::UNDER_OFFER => 'ri-time-line',
            default => 'ri-checkbox-circle-line',
        };
    }

    public function badgeHtml(): string
    {
        $styleAttr = $this->badgeStyle() ? ' style="' . $this->badgeStyle() . '"' : '';
        return '<span class="' . $this->badgeClass() . ' px-2 py-1 fs-12"' . $styleAttr . '><i class="' . $this->badgeIcon() . ' me-1"></i> ' . $this->label() . '</span>';
    }
}
