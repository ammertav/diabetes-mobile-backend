@extends('errors.layout')

@section('title', 'Access Forbidden')
@section('code', '403')
@section('icon', 'lock')
@section('message_title', 'Access Forbidden')
@section('message_body', $exception->getMessage() ?: 'You do not have the required permissions to access this resource or perform this action.')
