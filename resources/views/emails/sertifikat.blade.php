<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sertifikat PKL Telah Diterbitkan</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/image/logo-sie.png') }}">
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f4f7f8;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
    "
>

    <!-- Wrapper -->
    <table
        width="100%"
        cellpadding="0"
        cellspacing="0"
        border="0"
        style="
            background-color: #f4f7f8;
            padding: 40px 20px;
        "
    >
        <tr>
            <td align="center">

                <!-- Main Card -->
                <table
                    width="600"
                    cellpadding="0"
                    cellspacing="0"
                    border="0"
                    style="
                        max-width: 600px;
                        width: 100%;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
                    "
                >

                    <!-- Header -->
                    <tr>
                        <td
                            style="
                                background-color: #4C9FA3;
                                padding: 28px 35px;
                                text-align: center;
                            "
                        >
                            <div
                                style="
                                    font-size: 13px;
                                    color: #e9ffff;
                                    letter-spacing: 1px;
                                    margin-bottom: 8px;
                                "
                            >
                                MONITORING MAHASISWA PKL
                            </div>

                            <div
                                style="
                                    font-size: 24px;
                                    font-weight: bold;
                                    color: #ffffff;
                                "
                            >
                                Sertifikat PKL
                            </div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td
                            style="
                                padding: 35px;
                            "
                        >

                            <p
                                style="
                                    margin: 0 0 18px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >
                                Yth.
                                <strong>
                                    {{ $mahasiswa->nama_lengkap }}
                                </strong>,
                            </p>

                            <p
                                style="
                                    margin: 0 0 18px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >
                                Dengan hormat,
                            </p>

                            <p
                                style="
                                    margin: 0 0 18px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >
                                Kami informasikan bahwa
                                <strong>
                                    Sertifikat Praktik Kerja Lapangan (PKL)
                                </strong>
                                Anda telah selesai diterbitkan.
                            </p>

                            <!-- Information Box -->
                            <table
                                width="100%"
                                cellpadding="0"
                                cellspacing="0"
                                border="0"
                                style="
                                    margin: 25px 0;
                                    background-color: #f1f8f8;
                                    border-left: 4px solid #4C9FA3;
                                    border-radius: 6px;
                                "
                            >
                                <tr>
                                    <td
                                        style="
                                            padding: 18px 20px;
                                            font-size: 14px;
                                            line-height: 1.7;
                                            color: #555555;
                                        "
                                    >
                                        Sertifikat PKL telah dilampirkan
                                        pada email ini dalam bentuk dokumen
                                        digital. Silakan mengunduh dan
                                        menyimpan dokumen tersebut sebagai
                                        arsip pribadi.
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="
                                    margin: 0 0 18px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >
                                Mohon untuk memeriksa lampiran pada email ini
                                dan memastikan dokumen sertifikat dapat
                                dibuka dengan baik.
                            </p>

                            <p
                                style="
                                    margin: 0;
                                    font-size: 15px;
                                    line-height: 1.7;
                                "
                            >
                                Demikian informasi ini kami sampaikan.
                                Terima kasih atas perhatian dan kerja sama Anda
                                selama pelaksanaan PKL.
                            </p>

                            <!-- Signature -->
                            <table
                                width="100%"
                                cellpadding="0"
                                cellspacing="0"
                                border="0"
                                style="
                                    margin-top: 35px;
                                "
                            >
                                <tr>
                                    <td
                                        style="
                                            border-top: 1px solid #eeeeee;
                                            padding-top: 20px;
                                        "
                                    >
                                        <p
                                            style="
                                                margin: 0;
                                                font-size: 14px;
                                                line-height: 1.6;
                                                color: #555555;
                                            "
                                        >
                                            Hormat kami,
                                        </p>

                                        <p
                                            style="
                                                margin: 4px 0 0;
                                                font-size: 15px;
                                                font-weight: bold;
                                                color: #333333;
                                            "
                                        >
                                            Sistem Monitoring Mahasiswa PKL
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="
                                background-color: #f8fafb;
                                padding: 20px 35px;
                                text-align: center;
                                border-top: 1px solid #eeeeee;
                            "
                        >
                            <p
                                style="
                                    margin: 0;
                                    font-size: 12px;
                                    line-height: 1.6;
                                    color: #888888;
                                "
                            >
                                Email ini dikirim secara otomatis oleh
                                Sistem Monitoring Mahasiswa PKL.
                                <br>
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>