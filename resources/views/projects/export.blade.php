<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->name }}</title>
    @include('projects.partials.landing-styles')
    <style>
        .lp-section {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 700ms ease, transform 700ms ease;
        }

        .lp-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .lp-floating-tag {
            animation: lpFloat 5.5s ease-in-out infinite;
        }

        .lp-floating-tag.two {
            animation-delay: 1.2s;
        }

        @keyframes lpFloat {
            0%, 100% { transform: translateY(0) rotate(var(--lp-rotate, 0deg)); }
            50% { transform: translateY(-8px) rotate(var(--lp-rotate, 0deg)); }
        }
    </style>
</head>
<body style="margin:0;background:#020617;">
    @include('projects.partials.landing-render', ['pageData' => $pageData, 'mode' => 'render'])
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.lp-floating-tag.one').forEach((el) => el.style.setProperty('--lp-rotate', '10deg'));
            document.querySelectorAll('.lp-floating-tag.two').forEach((el) => el.style.setProperty('--lp-rotate', '-7deg'));

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.12 });

            document.querySelectorAll('.lp-section').forEach((section) => observer.observe(section));
        });
    </script>
</body>
</html>
