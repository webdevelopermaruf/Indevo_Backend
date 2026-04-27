<?php
namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExampleSkillSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        SkillStep::truncate();
        Skill::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $skills = [
            // LIVING
            [
                'title'       => 'Perfect Pasta',
                'achievement' => 'Pasta Pro',
                'poster'      => '🍝',
                'duration'    => 15,
                'reward'      => 20,
                'difficulty'  => 'easy',
                'category'    => 'Living',
                'steps' => [
                    [
                        'title'             => 'Gather your ingredients',
                        'short_description' => 'Prep before you cook',
                        'long_description'  => 'You\'ll need pasta, salt, and your chosen sauce. For 2 servings, use 200g of dried pasta. Lay everything out before turning on the heat — this is called "mise en place" and it makes cooking effortless.',
                        'picture'           => '🛒',
                        'video'             => 'https://www.youtube.com/watch?v=2oqGVmBKhPw',
                        'tip'               => 'Always weigh dry pasta — eyeballing leads to way too much or too little.',
                    ],
                    [
                        'title'             => 'Boil salted water',
                        'short_description' => 'The most underrated step',
                        'long_description'  => 'Fill a large pot with water and bring to a rolling boil. Add 1 tablespoon of salt — the water should taste like the sea. This is your only chance to season the pasta itself.',
                        'picture'           => '♨️',
                        'video'             => null,
                        'tip'               => 'Never add oil to the water — it makes sauce slide off the pasta later.',
                    ],
                    [
                        'title'             => 'Cook al dente',
                        'short_description' => 'Time it precisely',
                        'long_description'  => 'Add pasta to boiling water and stir immediately. Cook for 1 minute less than the packet says. Taste it — it should be firm in the centre. Save a cup of pasta water before draining.',
                        'picture'           => '⏱️',
                        'video'             => null,
                        'tip'               => 'Pasta water is liquid gold — the starch helps your sauce cling perfectly.',
                    ],
                    [
                        'title'             => 'Finish in the sauce',
                        'short_description' => 'The Italian secret',
                        'long_description'  => 'Add drained pasta directly into your sauce pan over low heat. Toss for 1-2 minutes, adding pasta water a splash at a time until silky. The pasta finishes cooking in the sauce and absorbs the flavour.',
                        'picture'           => '🍲',
                        'video'             => null,
                        'tip'               => 'Low heat is key — high heat makes the sauce dry out and stick.',
                    ],
                ],
            ],
            // FINANCE
            [
                'title'       => 'Monthly Budget',
                'achievement' => 'Money Wise',
                'poster'      => '💰',
                'duration'    => 30,
                'reward'      => 40,
                'difficulty'  => 'medium',
                'category'    => 'Financial Literacy',
                'steps' => [
                    [
                        'title'             => 'List your income',
                        'short_description' => 'Know what comes in',
                        'long_description'  => 'Write down every source of money you receive each month — wages, benefits, allowances, side income. Use after-tax figures. This is your total monthly income.',
                        'picture'           => '💵',
                        'video'             => null,
                        'tip'               => 'If your income varies, use your lowest month as a safe estimate.',
                    ],
                    [
                        'title'             => 'Track your expenses',
                        'short_description' => 'Know what goes out',
                        'long_description'  => 'List all your fixed expenses (rent, bills, subscriptions) and variable ones (food, transport, entertainment). Check your bank statements for the last 3 months to find patterns.',
                        'picture'           => '🧾',
                        'video'             => null,
                        'tip'               => 'Most people underestimate variable spending by 20-30%.',
                    ],
                    [
                        'title'             => 'Apply the 50/30/20 rule',
                        'short_description' => 'Divide your money wisely',
                        'long_description'  => '50% of income goes to needs (rent, food, bills). 30% goes to wants (eating out, hobbies). 20% goes to savings and debt repayment. Adjust these ratios to fit your situation.',
                        'picture'           => '📊',
                        'video'             => 'https://www.youtube.com/watch?v=HQzoZfc3GwQ',
                        'tip'               => 'Even saving 10% is better than saving nothing. Start small.',
                    ],
                    [
                        'title'             => 'Set your budget limits',
                        'short_description' => 'Create your spending plan',
                        'long_description'  => 'Based on your income and expense tracking, set a realistic limit for each category. Write it down or use a spreadsheet. Review it every week for the first month.',
                        'picture'           => '📝',
                        'video'             => null,
                        'tip'               => 'A budget isn\'t a restriction — it\'s a plan for spending guilt-free.',
                    ],
                    [
                        'title'             => 'Review and adjust',
                        'short_description' => 'Make it work for you',
                        'long_description'  => 'At the end of the month, compare your actual spending to your budget. Celebrate what went well. Adjust categories that didn\'t work. A good budget evolves over time.',
                        'picture'           => '✅',
                        'video'             => null,
                        'tip'               => 'Don\'t give up if you overspend — just adjust and keep going.',
                    ],
                ],
            ],
            // LIVING - Health
            [
                'title'       => 'Morning Routine',
                'achievement' => 'Early Riser',
                'poster'      => '🌅',
                'duration'    => 20,
                'reward'      => 30,
                'difficulty'  => 'easy',
                'category'    => 'Health & Wellness',
                'steps' => [
                    [
                        'title'             => 'Wake up at the same time',
                        'short_description' => 'Anchor your day',
                        'long_description'  => 'Choose a consistent wake-up time and stick to it, even on weekends. Your body has an internal clock — consistency trains it to wake up naturally and feel alert.',
                        'picture'           => '⏰',
                        'video'             => null,
                        'tip'               => 'Put your phone across the room so you have to get up to turn off the alarm.',
                    ],
                    [
                        'title'             => 'Hydrate immediately',
                        'short_description' => 'Drink water first',
                        'long_description'  => 'Drink a full glass of water within 5 minutes of waking. You\'ve been asleep for hours without water — your body is dehydrated. This simple step boosts energy and kickstarts metabolism.',
                        'picture'           => '💧',
                        'video'             => null,
                        'tip'               => 'Keep a glass of water on your bedside table the night before.',
                    ],
                    [
                        'title'             => 'Move your body',
                        'short_description' => '5 minutes is enough to start',
                        'long_description'  => 'Do 5-10 minutes of light movement — stretching, a short walk, or simple exercises. This gets blood flowing and signals to your brain that it\'s time to be awake and active.',
                        'picture'           => '🧘',
                        'video'             => null,
                        'tip'               => 'You don\'t need a full workout. Even stretching in bed counts.',
                    ],
                ],
            ],
            // CAREER
            [
                'title'       => 'Write a CV',
                'achievement' => 'Career Ready',
                'poster'      => '📄',
                'duration'    => 45,
                'reward'      => 60,
                'difficulty'  => 'medium',
                'category'    => 'Career',
                'steps' => [
                    [
                        'title'             => 'Choose the right format',
                        'short_description' => 'Structure matters',
                        'long_description'  => 'A chronological CV lists your most recent experience first and works for most people. A skills-based CV works better if you have gaps or are changing careers. Keep it to 1-2 pages maximum.',
                        'picture'           => '📋',
                        'video'             => null,
                        'tip'               => 'Use a clean, simple template — fancy designs can confuse application tracking systems.',
                    ],
                    [
                        'title'             => 'Write your personal statement',
                        'short_description' => 'Your 3-sentence pitch',
                        'long_description'  => 'Write 3-4 sentences at the top: who you are, what you\'re good at, and what you\'re looking for. Tailor this to each job. Avoid clichés like "hardworking team player" — be specific.',
                        'picture'           => '✍️',
                        'video'             => null,
                        'tip'               => 'Read it aloud — if it sounds robotic, rewrite it in your natural voice.',
                    ],
                    [
                        'title'             => 'List your experience',
                        'short_description' => 'Show what you\'ve done',
                        'long_description'  => 'For each role, include: job title, company name, dates, and 3-4 bullet points of what you achieved (not just what you did). Use action verbs: "managed", "created", "improved", "delivered".',
                        'picture'           => '💼',
                        'video'             => null,
                        'tip'               => 'Quantify achievements where possible — "increased sales by 15%" beats "helped with sales".',
                    ],
                    [
                        'title'             => 'Add education and skills',
                        'short_description' => 'Complete the picture',
                        'long_description'  => 'List your qualifications with dates and grades if strong. Add a skills section with relevant technical and soft skills. Include any certifications, languages, or volunteering that adds value.',
                        'picture'           => '🎓',
                        'video'             => null,
                        'tip'               => 'Only list skills you could confidently discuss in an interview.',
                    ],
                    [
                        'title'             => 'Proofread and save as PDF',
                        'short_description' => 'Polish before sending',
                        'long_description'  => 'Read your CV three times. Then ask someone else to read it. Check spelling, grammar, consistent formatting, and accurate dates. Save as a PDF to preserve the layout on any device.',
                        'picture'           => '🔍',
                        'video'             => null,
                        'tip'               => 'Name the file "FirstName_LastName_CV.pdf" — not "CV_final_v3_REAL.pdf".',
                    ],
                ],
            ],
            // LIVING - Home
            [
                'title'       => 'Clean Your Home',
                'achievement' => 'Clean Sweep',
                'poster'      => '🧹',
                'duration'    => 25,
                'reward'      => 20,
                'difficulty'  => 'easy',
                'category'    => 'Living',
                'steps' => [
                    [
                        'title'             => 'Declutter first',
                        'short_description' => 'Clear before you clean',
                        'long_description'  => 'Go room by room and put everything back in its place before cleaning. Cleaning around clutter is inefficient. Use a basket to collect items that belong in other rooms.',
                        'picture'           => '📦',
                        'video'             => null,
                        'tip'               => 'Set a 10-minute timer — you\'ll be surprised how much you can declutter.',
                    ],
                    [
                        'title'             => 'Clean top to bottom',
                        'short_description' => 'Work with gravity',
                        'long_description'  => 'Always clean from high surfaces to low ones — dust falls downward. Start with shelves and surfaces, then work down to countertops, and finish with the floor last.',
                        'picture'           => '🧽',
                        'video'             => null,
                        'tip'               => 'Microfibre cloths trap dust better than regular cloths and don\'t need chemicals.',
                    ],
                    [
                        'title'             => 'Vacuum and mop',
                        'short_description' => 'Finish with floors',
                        'long_description'  => 'Vacuum all floors including under furniture edges. If you have hard floors, mop after vacuuming. Work backwards towards the door so you don\'t step on clean floors.',
                        'picture'           => '🫧',
                        'video'             => null,
                        'tip'               => 'A quick 15-minute clean daily beats a 3-hour deep clean weekly.',
                    ],
                ],
            ],
            // CAREER - Communication
            [
                'title'       => 'Public Speaking Basics',
                'achievement' => 'Confident Speaker',
                'poster'      => '🎤',
                'duration'    => 30,
                'reward'      => 50,
                'difficulty'  => 'hard',
                'category'    => 'Communication',
                'steps' => [
                    [
                        'title'             => 'Know your audience',
                        'short_description' => 'Speak to them, not at them',
                        'long_description'  => 'Before preparing your speech, think about who will be listening. What do they already know? What do they care about? What do you want them to feel or do after you speak? This shapes everything.',
                        'picture'           => '👥',
                        'video'             => null,
                        'tip'               => 'Imagine explaining your topic to a friend — that natural tone is what you\'re aiming for.',
                    ],
                    [
                        'title'             => 'Structure your message',
                        'short_description' => 'Tell them what you\'ll tell them',
                        'long_description'  => 'Use a simple structure: Opening (grab attention), Middle (3 key points max), Closing (summarise and call to action). People remember beginnings and endings most — make them count.',
                        'picture'           => '📐',
                        'video'             => null,
                        'tip'               => 'If you can\'t summarise your talk in one sentence, you need to simplify it.',
                    ],
                    [
                        'title'             => 'Practice out loud',
                        'short_description' => 'Your mouth needs rehearsal too',
                        'long_description'  => 'Reading your speech in your head is not the same as speaking it. Practice out loud at least 3 times. Record yourself once — it\'s uncomfortable but reveals habits you can\'t notice otherwise.',
                        'picture'           => '🎙️',
                        'video'             => 'https://www.youtube.com/watch?v=tShavGuo0_E',
                        'tip'               => 'Slow down. Most nervous speakers talk too fast. Pauses feel longer to you than to the audience.',
                    ],
                    [
                        'title'             => 'Manage nerves',
                        'short_description' => 'Use the energy, don\'t fight it',
                        'long_description'  => 'Nervousness and excitement feel the same in your body. Take 3 slow deep breaths before speaking. Make eye contact with friendly faces. Remember: the audience wants you to succeed.',
                        'picture'           => '🧠',
                        'video'             => null,
                        'tip'               => 'Even professional speakers get nervous. The goal is not to eliminate nerves but to channel them.',
                    ],
                ],
            ],
            // HOBBY
            [
                'title'       => 'Start Journalling',
                'achievement' => 'Reflective Mind',
                'poster'      => '📓',
                'duration'    => 10,
                'reward'      => 20,
                'difficulty'  => 'easy',
                'category'    => 'Other',
                'steps' => [
                    [
                        'title'             => 'Choose your journal',
                        'short_description' => 'Pick what works for you',
                        'long_description'  => 'You can use a physical notebook or a digital app — whichever you\'ll actually use. Don\'t overthink it. A cheap notebook works just as well as an expensive one. The tool doesn\'t matter, the habit does.',
                        'picture'           => '📔',
                        'video'             => null,
                        'tip'               => 'Keep your journal somewhere visible so it reminds you to write.',
                    ],
                    [
                        'title'             => 'Write without judgement',
                        'short_description' => 'No rules, no wrong answers',
                        'long_description'  => 'Start with just 5 minutes a day. Write whatever comes to mind — your mood, what happened today, worries, goals, gratitude. Don\'t edit or judge yourself. This is private and just for you.',
                        'picture'           => '✏️',
                        'video'             => null,
                        'tip'               => 'If you don\'t know what to write, start with "Today I feel..." and keep going.',
                    ],
                    [
                        'title'             => 'Build the habit',
                        'short_description' => 'Consistency over perfection',
                        'long_description'  => 'Attach journalling to something you already do — morning coffee, before bed, after lunch. Missing a day is fine — just start again the next day without guilt. Even 2 sentences counts.',
                        'picture'           => '🔄',
                        'video'             => null,
                        'tip'               => 'After 2 weeks, re-read your earlier entries — you\'ll be surprised by the patterns you notice.',
                    ],
                ],
            ],
            // TIME MANAGEMENT
            [
                'title'       => 'Manage Your Time',
                'achievement' => 'Time Master',
                'poster'      => '⏰',
                'duration'    => 20,
                'reward'      => 40,
                'difficulty'  => 'medium',
                'category'    => 'Time Management',
                'steps' => [
                    [
                        'title'             => 'Audit how you spend your time',
                        'short_description' => 'You can\'t manage what you don\'t measure',
                        'long_description'  => 'For one day, write down everything you do in 30-minute blocks. Most people are shocked by how much time disappears into phones, indecision, and low-priority tasks. Awareness is the first step.',
                        'picture'           => '📊',
                        'video'             => null,
                        'tip'               => 'Be honest — this is for you, not anyone else.',
                    ],
                    [
                        'title'             => 'Prioritise with the Eisenhower Matrix',
                        'short_description' => 'Urgent vs Important',
                        'long_description'  => 'Divide your tasks into 4 boxes: Urgent & Important (do now), Important but not urgent (schedule it), Urgent but not important (delegate it), Neither (delete it). Most people spend too much time on urgent but unimportant tasks.',
                        'picture'           => '🗂️',
                        'video'             => 'https://www.youtube.com/watch?v=tT89OZ7TNwc',
                        'tip'               => 'Your most important tasks rarely feel urgent — that\'s why they get postponed.',
                    ],
                    [
                        'title'             => 'Time block your day',
                        'short_description' => 'Give every hour a job',
                        'long_description'  => 'Assign specific tasks to specific time slots in your calendar. Group similar tasks together. Schedule your hardest work during your peak energy hours. Leave buffer time between blocks.',
                        'picture'           => '📅',
                        'video'             => null,
                        'tip'               => 'Start with just 3 time blocks a day — morning focus, afternoon tasks, evening wind-down.',
                    ],
                ],
            ],
            // FINANCE - Saving
            [
                'title'       => 'Build an Emergency Fund',
                'achievement' => 'Safety Net',
                'poster'      => '🏦',
                'duration'    => 20,
                'reward'      => 50,
                'difficulty'  => 'medium',
                'category'    => 'Financial Literacy',
                'steps' => [
                    [
                        'title'             => 'Understand why it matters',
                        'short_description' => 'Your financial safety net',
                        'long_description'  => 'An emergency fund is money set aside for unexpected expenses — a broken boiler, job loss, or medical bill. Without one, people rely on credit cards or loans which create debt. With one, you can handle life\'s surprises without panic.',
                        'picture'           => '🛡️',
                        'video'             => null,
                        'tip'               => 'Even £500 saved can prevent most common financial emergencies.',
                    ],
                    [
                        'title'             => 'Set your target amount',
                        'short_description' => 'How much do you need?',
                        'long_description'  => 'The standard advice is 3-6 months of essential expenses. Calculate your monthly essentials — rent, bills, food, transport — and multiply by 3. That\'s your starter target. Start smaller if needed — even 1 month is a great first goal.',
                        'picture'           => '🎯',
                        'video'             => null,
                        'tip'               => 'Don\'t let perfect be the enemy of good — £1,000 saved beats £0 waiting for the perfect amount.',
                    ],
                    [
                        'title'             => 'Open a separate savings account',
                        'short_description' => 'Keep it out of sight',
                        'long_description'  => 'Open a dedicated savings account separate from your main account. This makes it harder to dip into accidentally and easier to track progress. Look for an easy-access account with a decent interest rate.',
                        'picture'           => '🏧',
                        'video'             => 'https://www.youtube.com/watch?v=Rm789_mMNic',
                        'tip'               => 'Many banks let you open a savings account in minutes via their app.',
                    ],
                    [
                        'title'             => 'Automate your savings',
                        'short_description' => 'Pay yourself first',
                        'long_description'  => 'Set up a standing order to move money into your emergency fund on payday — before you can spend it. Even £20-£50 per month adds up. Automating removes the decision and the temptation.',
                        'picture'           => '⚙️',
                        'video'             => null,
                        'tip'               => 'Treat your savings like a bill — non-negotiable and paid first.',
                    ],
                    [
                        'title'             => 'Only use it for real emergencies',
                        'short_description' => 'Protect what you\'ve built',
                        'long_description'  => 'A sale at your favourite shop is not an emergency. A broken washing machine is. Define your rules before you need the money. If you do use it, make replenishing it your first financial priority afterwards.',
                        'picture'           => '🔒',
                        'video'             => null,
                        'tip'               => 'Write down what counts as an emergency for you — it helps you stay firm when temptation strikes.',
                    ],
                ],
            ],
        ];

        foreach ($skills as $skillData) {
            $steps = $skillData['steps'];
            unset($skillData['steps']);

            $skillData['created_at'] = now();
            $skillData['updated_at'] = now();

            $skill = Skill::create($skillData);

            foreach ($steps as $position => $step) {
                SkillStep::create([
                    'skill_id'          => $skill->id,
                    'position'          => $position,
                    'title'             => $step['title'],
                    'short_description' => $step['short_description'],
                    'long_description'  => $step['long_description'],
                    'picture'           => $step['picture'],
                    'video'             => $step['video'] ?? null,
                    'tip'               => $step['tip'] ?? null,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }
}