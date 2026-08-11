<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        Sertifikat PKL
    </title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background: #f4f7f7;
        font-family: Arial, Helvetica, sans-serif;
    "
>

    <div
        style="
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        "
    >

        <h2
            style="
                margin-top: 0;
                color: #4C9FA3;
            "
        >
            Sertifikat PKL Telah Diterbitkan
        </h2>


        <p>
            Halo
            <strong>
                {{ $mahasiswa->nama_lengkap }}
            </strong>,
        </p>


        <p>
            Kami informasikan bahwa sertifikat
            Praktik Kerja Lapangan (PKL) Anda telah
            diterbitkan oleh perusahaan.
        </p>


        <p>
            Sertifikat tersebut telah dilampirkan
            pada email ini.
        </p>


        <p>
            Silakan simpan dokumen sertifikat tersebut
            sebagai arsip pribadi.
        </p>


        <p
            style="
                margin-top: 30px;
                margin-bottom: 0;
            "
        >
            Terima kasih.
        </p>


        <p
            style="
                margin-top: 5px;
                color: #666666;
            "
        >
            Sistem Monitoring Mahasiswa PKL
        </p>

    </div>

</body>

</html>