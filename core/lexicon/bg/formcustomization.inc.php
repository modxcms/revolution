<?php
/**
 * Form Customization English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['action'] = 'Действие';
$_lang['action_desc'] = 'Действието, за което това Правило ще се приложи.';
$_lang['activate'] = 'Активирай';
$_lang['constraint'] = 'Ограничение';
$_lang['constraint_class'] = 'Клас на ограничение';
$_lang['constraint_class_desc'] = 'Необязательно. Если задано значение, то вместе с ограничивающим полем и настройками ограничения, сведёт это правило к применённым ограничениям.';
$_lang['constraint_desc'] = 'По избор. Стойността на полето за ограничение ковто трябва да се провери.';
$_lang['constraint_field'] = 'Поле на ограничение';
$_lang['constraint_field_desc'] = 'По избор. Полето към което трябва да се приложи това ограничение.';
$_lang['containing_panel'] = 'Съдържащ панел';
$_lang['containing_panel_desc'] = 'ID на съдържащия Форм Панел, в който е полето. Това понякога е необходимо за някои правила, така че системата може да разбере в коя форма или панел се намира полето.';
$_lang['deactivate'] = 'Деактивирай';
$_lang['default_value'] = 'Стойност по подразбиране';
$_lang['export'] = 'Експорт';
$_lang['fc.action_create'] = 'Създай Ресурс';
$_lang['fc.action_update'] = 'Edit Resource';
$_lang['fc.action_resource_wildcard'] = 'Create & Edit Resource';
$_lang['field'] = 'Поле';
$_lang['field_desc'] = 'Това е полето на въздействие. Може да бъде също така таб или TV. Ако е TV моля задайте я в следния формат: "tv#", където # е ID-то на тази TV.';
$_lang['field_default'] = 'Стойност по подразбиране на Поле';
$_lang['field_label'] = 'Етикет на Поле';
$_lang['field_visible'] = 'Видимо поле';
$_lang['fields'] = 'Полета';
$_lang['file'] = 'Файл';
$_lang['filter_by_action'] = 'Филтриране по Действие...';
$_lang['filter_by_rule_type'] = 'Филтриране по Правило...';
$_lang['filter_by_search'] = 'Търсене...';
$_lang['for_parent'] = 'За родителски елемент';
$_lang['for_parent_desc'] = 'Отметни това, когато искаш това правило да се приложи за родителския елемент. Използвай само с Ресурси или обекти с `parent` поле. Полезно за "създаване" на страница от Ресурси.';
$_lang['form_customization_msg'] = 'Here is a list of currently applied Rules. More information on Rules and Form Customization can be found <a href="https://docs.modx.com/3.x/en/building-sites/client-proofing/form-customization" target="_blank">here</a>. Please note that improper Rules might cause problems with your MODX Revolution installation. Inactive Rules are faded gray.';
$_lang['form_rules'] = 'Форма Правила';
$_lang['import'] = 'Импорт';
$_lang['import_from_xml'] = 'Import Set from XML';
$_lang['label'] = 'Етикет';
$_lang['original_value'] = 'Първоначална стойност';
$_lang['profile'] = 'Профил';
$_lang['profile_create'] = 'Create Profile';
$_lang['profile_err_nfs'] = 'Не е намерена форма за персонализиране с ID [[+id]].';
$_lang['profile_err_ns'] = 'Не е зададена форма за персонализиране на профила!';
$_lang['profile_err_remove'] = 'An error occurred while trying to delete the Profile.';
$_lang['profile_err_save'] = 'Възникна грешка при опита за съхранение на профила.';
$_lang['profile_msg'] = 'Here you can specify Sets for this Profile. A Set is a collection of Rules that apply for a given page (Edit or Create Resource). They can also be restricted to certain Templates, or other field values on the Resource. Constraints for the Create Resource Sets will execute based on the parent of the newly created Resource\'s value.';
$_lang['profile_new'] = 'Create Form Customizaton Profile';
$_lang['profile_remove'] = 'Изтрийте профил';
$_lang['profile_remove_confirm'] = 'Are you sure you want to completely delete this Profile? This is irreversible.';
$_lang['profile_remove_multiple'] = 'Изтриване на няколко профила';
$_lang['profile_remove_multiple_confirm'] = 'Are you sure you want to completely delete these Profiles? This is irreversible.';
$_lang['profile_usergroup_err_ae'] = 'Потребителската група вече е зададена за този профил!';
$_lang['profile_usergroups_msg'] = 'Тук може да зададете потребителска група, която ще използва този профил. Ако не е зададена потребителска група, всички потребители ще използват този профил.';
$_lang['profiles'] = 'Форма за персонализиране на профила';
$_lang['profiles_msg'] = 'Списък на Вашата форма за персонализиране на профила. Профилите магат да съдържат много форми за персонализиране на правилата и могат да бъдат активирани или деактивирани. Могат също така да бъдат настроени да се прилагат към определени потребителски групи.';
$_lang['rank'] = 'Ранг';
$_lang['rank_desc'] = 'Редът по който се изпълнява правилото. По-малък номер означава, че ще бъде изпълнен по-рано.';
$_lang['region'] = 'Регион';
$_lang['regions'] = 'Региони';
$_lang['rule'] = 'Правило';
$_lang['rule_create'] = 'Създай правило';
$_lang['rule_desc'] = 'Типа правило, което ще бъде приложено към това поле.';
$_lang['rule_description_desc'] = 'По избор. Описание на правилото.';
$_lang['rule_err_ae'] = 'Вече съществува правило за това поле!';
$_lang['rule_err_duplicate'] = 'Възникна грешка при опита за дублиране на правилото.';
$_lang['rule_err_nf'] = 'Не е намерено правило.';
$_lang['rule_err_ns'] = 'Не е посочено правило.';
$_lang['rule_err_remove'] = 'An error occurred while trying to delete the rule.';
$_lang['rule_err_save'] = 'Възникна грешка при опита за записване на правилото.';
$_lang['rule_remove'] = 'Изтрии правило';
$_lang['rule_remove_confirm'] = 'Are you sure you want to delete this Rule?';
$_lang['rule_remove_multiple'] = 'Изтрийте няколко правила';
$_lang['rule_remove_multiple_confirm'] = 'Are you sure you want to delete these Rules? This is irreversible.';
$_lang['rule_update'] = 'Edit Rule';
$_lang['rule_value_desc'] = 'Стойността, която да се зададе в правилото.';
$_lang['rules'] = 'Правила';
$_lang['set'] = 'Задай';
$_lang['set_and_fields'] = 'Задайте информация и полета';
$_lang['set_change_template'] = 'Промяна шаблон за комплект';
$_lang['set_change_template_confirm'] = 'Сигурни ли сте, че искате да направите това? Това ще промени шаблона, за който важат тези правила. Ако е така, MODX първо ще запази промените преди да зареди страницата, за да обновите новите TV за новите шаблони.';
$_lang['set_constraint_field_desc'] = 'Настройване полето на ограничение ще попречи за изпълнение на правилото в този комплект , освен ако полето за този ресурс съответства на "ограничената" стойност.';
$_lang['set_constraint_desc'] = 'Задаване стойността на полето (посочено горе) ще попречи за изпълнение на правилото в този комплект , освен ако ресурса има стойност на зададеното ограничено поле.';
$_lang['set_create'] = 'Create Set';
$_lang['set_err_nfs'] = 'Не е намерен набор с ID [[+id]]';
$_lang['set_err_ns'] = 'Не е зададен комплект.';
$_lang['set_fields_msg'] = 'Here you can adjust the fields for this page, including their visibility, labels and default values. Just double-click on a row to edit its value. Leave a field empty to use the default setting.<br>Please note: when hiding an element inside this profile, it will be hidden in overlapping profiles too (even if Visible is checked).';
$_lang['set_import_err_upload'] = 'Възникна грешка при опит да се намери XML файла. Моля задайте валиден файл.';
$_lang['set_import_err_xml'] = 'Възникна грешка при импортиране на XML файл. Моля уверете се, че сте задали валиден XML файл за персонализация на формата.';
$_lang['set_import_msg'] = 'Изберете XML файл от който да импортирате форма за персонализиране на комплект. Трябва да е в правилния XML формат за персонализиране на форма.';
$_lang['set_import_template_err_nf'] = 'Template not found while import Form Customization Set.';
$_lang['set_msg'] = 'Тук можете да редактирате кои полета, табове и шаблон променливи да се показват за тази страница, както и техните етикети и стойности по подразбиране. Просто кликнете два пъти на колоната, за да редактирате стойността й. Можете също така да използвате таб ключа за да напредвате през решетката.  Оставете полето празно, за да използвате настройките по подразбиране.';
$_lang['set_new'] = 'Create Set';
$_lang['set_edit'] = 'Edit Set';
$_lang['set_remove'] = 'Изтрийте набор';
$_lang['set_remove_confirm'] = 'Are you sure you want to permanently delete this set? This is irreversable.';
$_lang['set_remove_multiple'] = 'Изтрийте множество комплекти';
$_lang['set_remove_multiple_confirm'] = 'Are you sure you want to permanently delete these sets? This is irreversable.';
$_lang['set_tab_err_ae'] = 'Вече съществува раздел с това ID. Моля задайте друг.';
$_lang['set_tabs_msg'] = 'Here you adjust the tabs and regions for this page, including their visibility and title. Just double-click on a row to edit its value. Leave a field empty to use the default setting.<br>Please note: when hiding an element inside this profile, it will be hidden in overlapping profiles too (even if Visible is checked).';
$_lang['set_template_desc'] = 'Избирайки шаблон ще ограничи правилата в комплекта от изпълнение, освен ако ресурса има зададен шаблон.';
$_lang['set_tvs_msg'] = 'Тук можете да зададете видимост, етикети, стойности по подразбиране и разделите в които пребивават чрез двойно кликване върху реда в таблицата. Забележка: Ако преместите TV в друга област, можете да регулирате реда на TV в полето "Регион ранг". Оставете полето празно, за да използвате настройките по подразбиране.';
$_lang['sets'] = 'Набор от форми за персонализиране';
$_lang['simplexml_err_nf'] = 'MODX изисква SimpleXML PHP разширение, за да използва тази функция. Моля уверете се, че разширението е инсталирано преди да продължите.';
$_lang['tab'] = 'Раздел (таб)';
$_lang['tab_create'] = 'Create Tab';
$_lang['tab_id'] = 'ID';
$_lang['tab_name'] = 'Име на раздел';
$_lang['tab_title'] = 'Заглавие';
$_lang['tab_new'] = 'Create Tab';
$_lang['tab_rank'] = 'Ранг на региона';
$_lang['tab_remove'] = 'Изтрийте раздел';
$_lang['tab_remove_confirm'] = 'Сигурни ли сте, че искате да изтриете този раздел?';
$_lang['tab_visible'] = 'Видим раздел';
$_lang['tabs'] = 'Раздели (Табове)';
$_lang['tv'] = 'Шаблон променлива';
$_lang['tv_default'] = 'TV стойност по подразбиране';
$_lang['tv_label'] = 'TV етикет';
$_lang['tv_name'] = 'Име';
$_lang['tv_move'] = 'Премести TV в радела';
$_lang['tv_visible'] = 'TV видим';
$_lang['tvs'] = 'Шаблон променливи';
$_lang['usergroup'] = 'Потребителска група';
$_lang['usergroup_create'] = 'Create User Group';
$_lang['usergroup_desc'] = 'По избор. Ако е зададено ще ограничи този профил само до потребители от определената потребителска група.';
$_lang['usergroup_remove'] = 'Delete User Group From Profile';
$_lang['usergroup_remove_confirm'] = 'Сигурни ли сте, че искате този профил повече да не се прилага към тази потребителска група?';
$_lang['usergroups'] = 'Потребителски групи';
$_lang['visible'] = 'Видимо';
$_lang['xmlwriter_err_nf'] = 'MODX изисква XMLWriter PHP разширение за да използва тази функция. Моля уверете се, че разширението е инсталирано преди да продължите.';
