PGDMP     6    ,                y            kok    12.2    13.2                0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    26304    kok    DATABASE     `   CREATE DATABASE kok WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'Russian_Russia.1251';
    DROP DATABASE kok;
                postgres    false            �            1259    26305    koks    TABLE     �   CREATE TABLE public.koks (
    id_link integer NOT NULL,
    original_link text NOT NULL,
    short_link text NOT NULL,
    hash_link text NOT NULL,
    using_count integer
);
    DROP TABLE public.koks;
       public         heap    postgres    false            �
          0    26305    koks 
   TABLE DATA           Z   COPY public.koks (id_link, original_link, short_link, hash_link, using_count) FROM stdin;
    public          postgres    false    202   _       
           2606    26312    koks koks_pkey 
   CONSTRAINT     Q   ALTER TABLE ONLY public.koks
    ADD CONSTRAINT koks_pkey PRIMARY KEY (id_link);
 8   ALTER TABLE ONLY public.koks DROP CONSTRAINT koks_pkey;
       public            postgres    false    202            �
   �   x�]�=k�0Eg�x�e%vZD�f
�дK�P��XX���T7��M�����}u ׸%�Ax���DyI��0���Њ��$�xGP��)�������G3Wx�h�1��I��9�n��B�k_��Y���M�y��ǷП3��a�f\|Ɏ;�-;�a���;�����ŐJW��4����� r{#!-�uY��B3\N�ݙ�C����F3)��1���o�     