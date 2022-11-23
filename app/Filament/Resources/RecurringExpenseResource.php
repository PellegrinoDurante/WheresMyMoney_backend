<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecurringExpenseResource\Pages;
use App\Models\RecurringExpense;
use App\Services\ChargeDataProvider\EmailAttachmentPdfChargeDataProvider;
use App\Services\ChargeDataProvider\EmailBodyScrapingChargeDataProvider;
use App\Services\ChargeDataProvider\EmailLinkScrapingChargeDataProvider;
use App\Services\ChargeDataProvider\UserDefinedChargeDataProvider;
use App\Services\Trigger\EmailTrigger;
use App\Services\Trigger\TemporalTrigger;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class RecurringExpenseResource extends Resource
{
    protected static ?string $model = RecurringExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(1)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('recurring_expense.name'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label(__('recurring_expense.description'))
                            ->maxLength(65535),
                    ]),
                Forms\Components\Section::make('trigger')
                    ->heading(__('recurring_expense.trigger.title'))
                    ->description(__('recurring_expense.trigger.description'))
                    ->schema([
                        Forms\Components\Select::make('trigger_type')
                            ->options([
                                TemporalTrigger::TYPE => __(sprintf('recurring_expense.trigger.%s.title', TemporalTrigger::TYPE)),
                                EmailTrigger::TYPE => __(sprintf('recurring_expense.trigger.%s.title', EmailTrigger::TYPE)),
                            ])
                            ->reactive(),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('trigger_user_defined_cron')
                                ->label(__('recurring_expense.trigger.temporal.cron'))
                        ])->hidden(fn(Closure $get) => $get('trigger_type') != TemporalTrigger::TYPE),

                        Forms\Components\Group::make([
                            /*Forms\Components\ViewField::make('google_login')
                                ->view('google')
                                ->hidden(fn(Closure $get) => Auth::user()->accessToken()->exists()),*/

                            Forms\Components\TextInput::make('trigger_email_subject')
                                ->label(__('recurring_expense.trigger.email.subject')),
                        ])->hidden(fn(Closure $get) => $get('trigger_type') != EmailTrigger::TYPE),
                    ]),

                Forms\Components\Section::make('charge_data_provider')
                    ->heading(__('recurring_expense.charge_data_provider.title'))
                    ->description(__('recurring_expense.charge_data_provider.description'))
                    ->schema([
                        Forms\Components\Select::make('charge_data_provider_type')
                            ->options([
                                UserDefinedChargeDataProvider::TYPE => __(sprintf('recurring_expense.charge_data_provider.%s.title', UserDefinedChargeDataProvider::TYPE)),
                                EmailLinkScrapingChargeDataProvider::TYPE => __(sprintf('recurring_expense.charge_data_provider.%s.title', EmailLinkScrapingChargeDataProvider::TYPE)),
                                EmailAttachmentPdfChargeDataProvider::TYPE => __(sprintf('recurring_expense.charge_data_provider.%s.title', EmailAttachmentPdfChargeDataProvider::TYPE)),
                                EmailBodyScrapingChargeDataProvider::TYPE => __(sprintf('recurring_expense.charge_data_provider.%s.title', EmailBodyScrapingChargeDataProvider::TYPE)),
                            ])
                            ->reactive(),

                        Forms\Components\Group::make([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_user_defined_value')
                                        ->label(__('recurring_expense.charge_data_provider.user_defined.amount'))
                                        ->mask(fn(Forms\Components\TextInput\Mask $mask) => $mask->money(prefix: '€', isSigned: false)),
                                    Forms\Components\DateTimePicker::make('cdp_user_defined_charged_at')
                                        ->label(__('recurring_expense.charge_data_provider.user_defined.charged_at')),
                                ]),
                        ])->hidden(fn(Closure $get) => $get('charge_data_provider_type') != UserDefinedChargeDataProvider::TYPE),

                        Forms\Components\Group::make([
                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_link_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.link_xpath')),
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_amount_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.amount_xpath')),
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_charged_at_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.charged_at_xpath')),
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_charged_at_format')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.charged_at_format')),
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_date_locale')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.date_locale')),
                                    Forms\Components\TextInput::make('cdp_email_link_scraping_click_before_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_link_scraping.click_before_xpath')),
                                ]),
                        ])->hidden(fn(Closure $get) => $get('charge_data_provider_type') != EmailLinkScrapingChargeDataProvider::TYPE),

                        Forms\Components\Group::make([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_index')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.index'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_page')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.page'))
                                        ->numeric()
                                        ->minValue(0),
                                ]),
                            Forms\Components\Grid::make(4)
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_amount_pos_x')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.amount_pos_x'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_amount_pos_y')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.amount_pos_y'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_amount_width')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.amount_width'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_amount_height')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.amount_height'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_charged_at_pos_x')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.charged_at_pos_x'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_charged_at_pos_y')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.charged_at_pos_y'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_charged_at_width')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.charged_at_width'))
                                        ->numeric()
                                        ->minValue(0),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_charged_at_height')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.charged_at_height'))
                                        ->numeric()
                                        ->minValue(0),
                                ]),
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_charged_at_format')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.charged_at_format')),
                                    Forms\Components\TextInput::make('cdp_email_attachment_pdf_date_locale')
                                        ->label(__('recurring_expense.charge_data_provider.email_attachment_pdf.date_locale')),
                                ]),
                        ])->hidden(fn(Closure $get) => $get('charge_data_provider_type') != EmailAttachmentPdfChargeDataProvider::TYPE),

                        Forms\Components\Group::make([
                            Forms\Components\Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('cdp_email_body_scraping_amount_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_body_scraping.amount_xpath')),
                                    Forms\Components\TextInput::make('cdp_email_body_scraping_charged_at_xpath')
                                        ->label(__('recurring_expense.charge_data_provider.email_body_scraping.charged_at_xpath')),
                                    Forms\Components\TextInput::make('cdp_email_body_scraping_charged_at_format')
                                        ->label(__('recurring_expense.charge_data_provider.email_body_scraping.charged_at_format')),
                                    Forms\Components\TextInput::make('cdp_email_body_scraping_date_locale')
                                        ->label(__('recurring_expense.charge_data_provider.email_body_scraping.date_locale')),
                                ]),
                        ])->hidden(fn(Closure $get) => $get('charge_data_provider_type') != EmailBodyScrapingChargeDataProvider::TYPE),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('recurring_expense.name')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('recurring_expense.created_at'))
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecurringExpenses::route('/'),
            'create' => Pages\CreateRecurringExpense::route('/create'),
            'view' => Pages\ViewRecurringExpense::route('/{record}'),
            'edit' => Pages\EditRecurringExpense::route('/{record}/edit'),
        ];
    }
}
