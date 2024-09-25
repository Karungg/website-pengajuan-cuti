<?php

namespace App\Filament\Resources;

use App\Enum\StatusRequest;
use App\Enum\TypeRequest;
use App\Filament\Resources\ApproveRequestResource\Pages;
use App\Filament\Resources\ApproveRequestResource\RelationManagers;
use App\Models\Request;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApproveRequestResource extends Resource
{
    protected static ?string $model = Request::class;

    protected static ?string $label = 'Approval Pengajuan';

    protected static ?string $navigationGroup = 'Transaksi Approval';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->label('NIP'),
                Forms\Components\TextInput::make('name')
                    ->label('Nama'),
                Forms\Components\ToggleButtons::make('type')
                    ->label('Kategori Ajuan')
                    ->options(TypeRequest::class)
                    ->inline()
                    ->required()
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
                    ->after('start_date')
                    ->validationMessages([
                        'after' => 'Tanggal Selesai tidak boleh sebelum dari Tanggal Mulai'
                    ]),
                Forms\Components\TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required(),
                Forms\Components\TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required()
                    ->after('start_time')
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
                $user = auth()->user();
                $status = null;

                if ($user->isHeadOfDivision()) {
                    $status = StatusRequest::Zero;
                    $role = 'employee';
                } elseif ($user->isResource()) {
                    $status = StatusRequest::One;
                    $role = 'employee';
                }

                if ($status !== null) {
                    $query->whereHas('user.roles', function (Builder $query) use ($role) {
                        $query->where('roles.name', $role);
                    })->where('status', $status)
                        ->whereNot('user_id', auth()->id());
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('user.nip')
                    ->hidden(auth()->user()->isEmployee())
                    ->label('NIP'),
                Tables\Columns\TextColumn::make('user.name')
                    ->hidden(auth()->user()->isEmployee())
                    ->label('Name'),
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
            'index' => Pages\ListApproveRequests::route('/'),
            'view' => Pages\ViewApproveRequest::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return !auth()->user()->isEmployee();
    }
}
