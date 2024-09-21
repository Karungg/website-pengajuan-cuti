<?php

namespace App\Filament\Clusters\Request\Resources;

use App\Enum\StatusRequest;
use App\Filament\Clusters\Request;
use App\Filament\Clusters\Request\Resources\RequestResource\Pages;
use App\Filament\Clusters\Request\Resources\RequestResource\RelationManagers;
use App\Models\Request as ModelRequest;
use App\Enum\TypeRequest;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RequestResource extends Resource
{
    protected static ?string $model = ModelRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-circle';

    protected static ?string $navigationLabel = 'Pengajuan';

    protected static ?string $pluralLabel = 'Pengajuan';

    protected static ?string $cluster = Request::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('type')
                    ->label('Kategori Ajuan')
                    ->options(TypeRequest::class)
                    ->inline()
                    ->required()
                    ->rules([
                        fn(): Closure => function (string $attribute, $value, Closure $fail) {
                            if ($value == 'leave') {
                                $leaveAllowance = User::query()
                                    ->where('id', auth()->id())
                                    ->value('leave_allowance');

                                if ($leaveAllowance <= 0) {
                                    $fail('Jatah cuti anda sudah habis.');
                                }
                            }
                        }
                    ])
                    ->live()
                    ->validationMessages([
                        'required' => 'Kategori Ajuan harus diisi.'
                    ]),
                Forms\Components\ToggleButtons::make('status')
                    ->inline()
                    ->options(function (?Model $record) {
                        return match ($record->status) {
                            StatusRequest::Zero => ['Pending'],
                            StatusRequest::One => ['Disetujui Kepala Divisi'],
                            StatusRequest::Two => ['Disetujui SDM'],
                            StatusRequest::Three => ['Disetujui Direktur'],
                            StatusRequest::Four => ['Ditolak'],
                        };
                    })
                    ->hiddenOn(['create', 'edit']),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Dari Tanggal')
                    ->required(),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Sampai Tanggal')
                    ->required()
                    ->afterOrEqual(fn(Get $get) => $get('start_date'))
                    ->validationMessages([
                        'after_or_equal' => 'Sampai Tanggal harus lebih dari atau sama dengan Tanggal Mulai'
                    ]),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->default(fn(Get $get): ?string => $get('type') != 'permission' ? '09:00' : null)
                    ->readOnly(fn(Get $get): bool => $get('type') != 'permission')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required()
                    ->after('start_time')
                    ->default(fn(Get $get): ?string => $get('type') != 'permission' ? '17:00' : null)
                    ->readOnly(fn(Get $get): bool => $get('type') != 'permission')
                    ->validationMessages([
                        'after' => 'Jam Selesai tidak boleh kurang dari Jam Mulai.'
                    ]),
                Forms\Components\ToggleButtons::make('condition')
                    ->label('Lokasi')
                    ->inline()
                    ->options([
                        true => 'Dalam Kota',
                        false => 'Luar Kota'
                    ])->colors([
                        true => 'primary',
                        false => 'danger'
                    ])->icons([
                        true => 'heroicon-m-home',
                        false => 'heroicon-m-arrow-right-start-on-rectangle'
                    ])
                    ->required()
                    ->live()
                    ->hiddenOn('view')
                    ->validationMessages([
                        'required' => 'Lokasi harus diisi.'
                    ]),
                Forms\Components\Textarea::make('location')
                    ->label('Detail Lokasi')
                    ->required()
                    ->maxLength(256)
                    ->columnSpanFull()
                    ->visible(fn(Get $get): bool => !$get('condition')),
                Forms\Components\Textarea::make('description')
                    ->label('Alasan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdminDirector()) {
                    $query->where('user_id', auth()->id());
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('user.nip')
                    ->hidden(!auth()->user()->isAdminDirector())
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->hidden(!auth()->user()->isAdminDirector()),
                Tables\Columns\TextColumn::make('type')
                    ->label('Kategori Ajuan')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Jam Mulai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->sortable()
                    ->label('Jam Selesai'),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRequests::route('/'),
            'create' => Pages\CreateRequest::route('/create'),
            'view' => Pages\ViewRequest::route('/{record}'),
            'edit' => Pages\EditRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !auth()->user()->isAdmin() && !auth()->user()->isDirector();
    }

    public static function canEdit(Model $record): bool
    {
        return $record->status == StatusRequest::Zero;
    }
}
