<?php

namespace App\Types;

class PermissionType
{
  public const CAN_CREATE_USER = 'create-user';
  public const CAN_UPDATE_USER = 'update-user';
  public const CAN_DELETE_USER = 'delete-user';

  public const CAN_CREATE_CATEGORY = 'create-category';
  public const CAN_UPDATE_CATEGORY = 'update-category';
  public const CAN_DELETE_CATEGORY = 'delete-category';

  public const CAN_CREATE_TAG = 'create-tag';
  public const CAN_UPDATE_TAG = 'update-tag';
  public const CAN_DELETE_TAG = 'delete-tag';

  public const CAN_CREATE_PRODUCT = 'create-product';
  public const CAN_UPDATE_PRODUCT = 'update-product';
  public const CAN_DELETE_PRODUCT = 'delete-product';

  public const CAN_CREATE_BILLBOARD = 'create-billboard';
  public const CAN_UPDATE_BILLBOARD = 'update-billboard';
  public const CAN_DELETE_BILLBOARD = 'delete-billboard';

}
