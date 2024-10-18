<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengumuman</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        html,
        body {
            height: 100%;
        }

        /* Container to center the card */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .card {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 400px;
            border-radius: 24px;
            line-height: 1.6;
            transition: all 0.64s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 24px;
            padding: 36px;
            border-radius: 24px;
            background: transparent;
            color: #000000;
            z-index: 1;
            transition: all 0.64s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            background-color: #0a3cff;
            border-radius: inherit;
            height: 100%;
            width: 100%;
            opacity: 0;
            transform: skew(-24deg);
            clip-path: circle(0% at 50% 50%);
            transition: all 0.64s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .content .heading {
            font-weight: 700;
            font-size: 36px;
            line-height: 1.3;
            z-index: 1;
        }

        .content .para {
            z-index: 1;
            opacity: 0.8;
            font-size: 18px;
        }

        .content .para-sm {
            font-size: 16px;
        }

        .card:hover::before {
            opacity: 1;
            transform: skew(0deg);
            clip-path: circle(140.9% at 0 0);
        }

        .card:hover .content {
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="content">
                <p class="heading">Pengumuman</p>
                <p class="para">
                    Penanda Tangan : {{ $approvedBy->name }}
                </p>
                <p class="para">
                    Tanggal Surat : {{ $requestLog[0]->created_at }}
                </p>
                <p class="para">
                    Perihal : Formulir Cuti Tahunan
                </p>

                @if ($approvedBy->hasRole('director'))
                    <p class="para para-sm">Tanda Tangan {{ $approvedBy->name }}</p>
                    <img style="width: 100px" src="{{ asset('storage/' . $approvedBy->signature) }}" alt="">
                @else
                    <p class="para para-sm">Tanda Tangan {{ $approvedBy->name }}</p>
                    <img style="width: 100px" src="{{ asset('storage/' . $approvedBy->signature) }}" alt="">

                    @hasanyrole(['employee', 'headOfDivision'])
                        @php
                            $headOfDivision = \App\Models\User::query()->findOrFail($requestLog[2]->user_id, [
                                'name',
                                'signature',
                            ]);
                        @endphp
                        <p class="para para-sm">Tanda Tangan {{ $headOfDivision->name }}</p>
                        <img style="width: 100px" src="{{ asset('storage/' . $headOfDivision->signature) }}" alt="">
                    @endhasanyrole
                @endif

            </div>
        </div>
    </div>
</body>

</html>
