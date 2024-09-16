<?php

declare(strict_types=1);

namespace DZunke\NovDoc\Domain\Settings;

use DZunke\NovDoc\Domain\Settings\Settings\Calendar;
use DZunke\NovDoc\Domain\Settings\Settings\ChatbotGeneral;
use DZunke\NovDoc\Domain\Settings\Settings\ChatbotSystemPrompt;
use DZunke\NovDoc\Domain\Settings\Settings\Holiday;
use DZunke\NovDoc\Domain\Settings\Settings\MoonCalendar;

/**
 * @phpstan-import-type ChatbotGeneralSettings from ChatbotGeneral
 * @phpstan-import-type ChatbotSystemPromptSettings from ChatbotSystemPrompt
 * @phpstan-import-type CalendarSettings from Calendar
 * @phpstan-import-type MoonCalendarSettings from MoonCalendar
 * @phpstan-import-type HolidaySettings from Holiday
 * @phpstan-type SettingsArray = array{
 *     chatbot: array{
 *         general: ChatbotGeneralSettings,
 *         system_prompt: ChatbotSystemPromptSettings
 *     },
 *     calendar: array{
 *         general: CalendarSettings,
 *         moon: MoonCalendarSettings,
 *         holiday: HolidaySettings
 *     }
 * }
 */
class Settings
{
    private ChatbotGeneral $chatbotGeneral;
    private ChatbotSystemPrompt $chatbotSystemPrompt;
    private Calendar $calendar;
    private MoonCalendar $moonCalendar;
    private Holiday $holiday;

    public function __construct()
    {
        $this->chatbotGeneral      = new ChatbotGeneral();
        $this->chatbotSystemPrompt = new ChatbotSystemPrompt();
        $this->calendar            = new Calendar();
        $this->moonCalendar        = new MoonCalendar();
        $this->holiday             = new Holiday();
    }

    /** @param SettingsArray $settingsArray */
    public static function fromArray(array $settingsArray): Settings
    {
        $settings = new Settings();
        $settings->setChatbotGeneral(ChatbotGeneral::fromArray($settingsArray['chatbot']['general']));
        $settings->setChatbotSystemPrompt(ChatbotSystemPrompt::fromArray($settingsArray['chatbot']['system_prompt']));
        $settings->setCalendar(Calendar::fromArray($settingsArray['calendar']['general']));
        $settings->setHoliday(Holiday::fromArray($settingsArray['calendar']['holiday']));
        $settings->setMoonCalendar(MoonCalendar::fromArray($settingsArray['calendar']['moon']));

        return $settings;
    }

    /** @return SettingsArray */
    public function toArray(): array
    {
        return [
            'chatbot' => [
                'general' => $this->chatbotGeneral->toArray(),
                'system_prompt' => $this->chatbotSystemPrompt->toArray(),
            ],
            'calendar' => [
                'general' => $this->calendar->toArray(),
                'moon' => $this->moonCalendar->toArray(),
                'holiday' => $this->holiday->toArray(),
            ],
        ];
    }

    public function getChatbotGeneral(): ChatbotGeneral
    {
        return $this->chatbotGeneral;
    }

    public function setChatbotGeneral(ChatbotGeneral $chatbotGeneral): void
    {
        $this->chatbotGeneral = $chatbotGeneral;
    }

    public function getChatbotSystemPrompt(): ChatbotSystemPrompt
    {
        return $this->chatbotSystemPrompt;
    }

    public function setChatbotSystemPrompt(ChatbotSystemPrompt $chatbotSystemPrompt): void
    {
        $this->chatbotSystemPrompt = $chatbotSystemPrompt;
    }

    public function getCalendar(): Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(Calendar $calendar): void
    {
        $this->calendar = $calendar;
    }

    public function getMoonCalendar(): MoonCalendar
    {
        return $this->moonCalendar;
    }

    public function setMoonCalendar(MoonCalendar $moonCalendar): void
    {
        $this->moonCalendar = $moonCalendar;
    }

    public function getHoliday(): Holiday
    {
        return $this->holiday;
    }

    public function setHoliday(Holiday $holiday): void
    {
        $this->holiday = $holiday;
    }
}
